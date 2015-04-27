<?php

/**
 * This is the model class for table "sites".
 *
 * The followings are the available columns in table 'sites':
 * @property integer $id
 * @property integer $ftp_id
 * @property string $description
 * @property string $created
 * @property string $checked
 * @property string $state
 * @property integer $check_interval
 */
class Site extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Site the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sites';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ftp_id, description, state, check_interval', 'required'),
			array('ftp_id, check_interval', 'numerical', 'integerOnly'=>true),
			array('state', 'length', 'max'=>8),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, ftp_id, description, created, checked, state, check_interval', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		    'ftp' => array(self::BELONGS_TO, 'Ftp', 'ftp_id'),
		    'revisions' => array(self::HAS_MANY, 'Revision', 'site_id'),
            'excluded' => array(self::HAS_MANY, 'ExcludedFiles', 'site_id'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ftp_id' => 'Ftp',
			'description' => 'Description',
			'created' => 'Created',
			'checked' => 'Checked',
			'state' => 'State',
			'check_interval' => 'Check Interval (in hours)',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('ftp_id',$this->ftp_id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('checked',$this->checked,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('check_interval',$this->check_interval);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination' => array(
              'pageSize' => 50,
            ),
		));
	}

	public function rehash($force_new = false) {
	    if ($this->state == 'indexing') {
	        return true;
	    }
	    $this->state = 'indexing';
        $this->save(false, array('state'));
	    Yii::app()->l->log('Rehashing '.$this->id. ' started');
	    
	    Yii::log('start FTP fetch');
	    try {
	        $files = Yii::app()->reader->connect($this->ftp)->getFiles($this->ftp->basepath);
	    } catch (Exception $e) {
	        Yii::app()->l->log('Site '.$this->id . ", error: " . $e->getMessage(), 'bad');
	        $this->state = 'error';
            $this->save(false, array('state'));
	        return false;
	    }

	    Yii::log('end FTP fetch');
	    // Now - check for top_level files
	    $top_level = Yii::app()->reader->getTopLevel();
	    
        if (!$force_new && $this->checkTopLevel($top_level)) {
            // We're not creating new revision
            // And all hashes are compared
            $rehash_res = 'ok';
            Yii::log('end checking');
        } else {
            // We're doinf forced rehash or we're having wring file hashes    	    
    	    $dbc = new CDbCriteria();
    	    $dbc->compare('site_id', $this->id);
    	    $dbc->order = 'id desc';
    
    	    $revision = Revision::model()->find($dbc);
    	    if (!$revision || $force_new) {
    	        $revision = Revision::model()->createNew($this->id);
    	    }
            $rehash_res = 'ok';
    
            $exclude = ExcludedFiles::model();
    
    	    foreach ($files as $file) {
    	        if (!$exclude->checkFile($this->id, $file['filename'])) {
    	            continue;
    	        }
    
    	        $fObj = new File();
    	        if ($force_new) {
    	            $fObj->add($this->id, $file, $revision->id);
    	        } else {
    	            $ok = $fObj->addCheck($this->id, $file, $revision->id);
    	            //Yii::log(var_export($ok, true));
    	            if (!$ok) {
    	                $rehash_res = 'error';
    
    	            }
    	        }
    	    }
    	    //$revision->state = $rehash_res;
    	    //$revision->save(false, 'updated');
            if ($rehash_res == 'error') {
                $dbc = new CDbCriteria();
                $dbc->compare('site_id', $this->id);
                $dbc->order = 'id desc';
    
                $last_revision = Revision::model()->find($dbc);
    
                Yii::app()->utils->mail(array(
                    'subject' => 'Error in site' . $this->description,
                    'body' => array('site'=>$this, 'revision' => $last_revision),
                    'view' => 'error',
                ));
            }
        }
	    $this->checked = date('Y-m-d H:i:s');
	    $this->save();
	    $this->state = $rehash_res;
        $this->save(false, array('state'));
	    Yii::app()->l->log('Rehashing '.$this->id. ' ended');
	    return ($rehash_res == 'ok');

	}

	private function checkTopLevel($top_level) {
	    $dbc = new CDbCriteria();
	    $dbc->compare('site_id', $this->id);
	    $dbc->order = 'id desc';

	    $revision = Revision::model()->find($dbc);
        
	    $dbx = new CDbCriteria();
	    $dbx->addCondition(' parent_file = "" ');
	    $dbx->compare('revision_id', $revision->id);
	    $files = File::model()->findAll($dbx);
	    foreach($files as $file) {
	        foreach ($top_level as $new_file) {
	            if ($file->fullpath == $new_file['filename'] 
	                && $file->size == $new_file['size']
	                && $file->time == strtotime($new_file['date'])
	                && $file->inner_hash == $new_file['inner_hash']) {
	                    continue 2; // End current iteration; go to next file
	                }
	        }
	        return false;
	    }
	    return true;
	    
	}
	
	
	protected function beforeSave() {
	    if(parent::beforeSave()) {
	        if ($this->isNewRecord) {
	            $this->created = date('Y-m-d H:i:s');
	        }

	        return true;
	    }
	}

	public function doImport() {
	    $allExternal = RemoteFtp::model()->findAll();
	    $allInternal = Ftp::model()->findAll();
	    
	    //print_r($allExternal);
	    //print_r($allInternal);
	    
	    
	    // 1. go through all internal FTP and check if there's a record for it
	    foreach ($allInternal as $intFtp) {
	        $found = false;
	        foreach ($allExternal as $extFtp) {
	            if ($intFtp->host == $extFtp->ftp_host && $intFtp->user == $extFtp->ftp_user) {
	                // Replace FTP params
	                $intFtp->pass = $extFtp->ftp_password;
	                $intFtp->basepath = $extFtp->ftp_path;
	                $intFtp->save();
	                // Put site in stopped state based on active state of FTP
	                $site = Site::model()->findByAttributes(array('ftp_id' => $intFtp->id));
	                if ($site) {
	                    if ($site->state == 'ok' && $extFtp->active == '0') {
	                        if ($site->rehash()) {
	                            $site->state = 'clean';
	                        }
	                    } elseif ($site->state == 'clean' && $extFtp->active == '1') {
	                        $site->state = 'ok';
	                    }
	                    $site->save();
	                }
	                
	                Yii::app()->l->log("Renewed FTP ".$intFtp->host); 
	                $found = true;
	               
	            }
	        }
	        if (!$found) {
	            $site = Site::model()->findByAttributes(array('ftp_id' => $intFtp->id));
	            if ($site) {
    	            $site->state = 'noftp';
    	            $site->ftp_id = 0;
    	            $site->save(false);
    	            
	            }
	            Yii::app()->l->log("Deleted FTP ".$intFtp->host);
	            $intFtp->delete();
	        }
	    }
	    foreach ($allExternal as $extFtp) {
	        $found = false;
	        foreach ($allInternal as $intFtp) {
	            if ($intFtp->host == $extFtp->ftp_host && $intFtp->user == $extFtp->ftp_user) {
	                // already updated
	                $found = true;
	            }
	        }
	        //TODO: no internal found, create new FTP and Site
	        if (!$found) {
	            $newFtp = new Ftp();
	            $newFtp->host = $extFtp->ftp_host;
	            $newFtp->user = $extFtp->ftp_user;
	            $newFtp->pass = $extFtp->ftp_password;
                $newFtp->basepath = rtrim($extFtp->ftp_path, '/') . '/'; 
                $newFtp->parsertype = 'default'; 
	            $newFtp->save();
	            //print_r($newFtp);
	            // Add site by FTP
	            $site = new Site();
	            $site->ftp_id = $newFtp->id;
	            $site->description = $newFtp->user . "@" . $newFtp->host;
	            $site->state = 'new';
	            $site->check_interval = 1;
	            $site->save();
	            //print_r($site);
	            Yii::app()->l->log("Added new FTP ".$newFtp->host);
	        }
	    }
	}
    
	function beforeDelete() {
	    if (parent::beforeDelete()) {
	        $c = new CDbCriteria();
	        $c->compare('site_id', $this->id);
	        Revision::model()->deleteAll($c);
	        return true;
	    }
	}
	
	
	
}
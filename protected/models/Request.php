<?php

/**
 * This is the model class for table "request".
 *
 * The followings are the available columns in table 'request':
 * @property integer $id
 * @property integer $owner_id
 * @property string $external_id
 * @property integer $req_type
 * @property string $nickname
 * @property string $created
 * @property string $updated
 * @property integer $status_id
 * @property string $comment
 *
 * The followings are the available model relations:
 * @property Statuses $status
 * @property Profiles $owner
 * @property ReqTypes $reqType
 */
class Request extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'request';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('owner_id, external_id, req_type, nickname, created, updated, status_id, comment', 'required'),
			array('owner_id, req_type, status_id', 'numerical', 'integerOnly'=>true),
			array('external_id', 'length', 'max'=>32),
			array('nickname', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, owner_id, external_id, req_type, nickname, created, updated, status_id, comment', 'safe', 'on'=>'search'),
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
			'status' => array(self::BELONGS_TO, 'Statuses', 'status_id'),
			'owner' => array(self::BELONGS_TO, 'Profiles', 'owner_id'),
			'reqType' => array(self::BELONGS_TO, 'ReqTypes', 'req_type'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'owner_id' => 'Owner',
			'external_id' => 'External',
			'req_type' => 'Req Type',
			'nickname' => 'Nickname',
			'created' => 'Created',
			'updated' => 'Updated',
			'status_id' => 'Status',
			'comment' => 'Comment',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('external_id',$this->external_id,true);
		$criteria->compare('req_type',$this->req_type);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Request the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

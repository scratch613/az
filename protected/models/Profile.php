<?php

/**
 * This is the model class for table "profiles".
 *
 * The followings are the available columns in table 'profiles':
 * @property integer $id
 * @property string $fname
 * @property string $mname
 * @property string $lname
 * @property string $nickname
 * @property string $email
 * @property string $phone
 * @property string $login
 * @property string $password
 */
class Profile extends CActiveRecord
{
	private $oldPass;
	public $password2;


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'profiles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fname, mname, lname, nickname, email, phone, login, password, password2', 'required'),
			array('fname, mname, lname, nickname, email, phone, login, password', 'length', 'max'=>255),

			// Add custom check for email
			array('email', 'email'),
			array('phone', 'checkPhone'),
			array('login', 'unique'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, fname, mname, lname, nickname, email, phone, login, password', 'safe', 'on'=>'search'),
			array('password2', 'compare', 'compareAttribute'=>'password', 'message'=>"Пароли не совпадают")
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'fname' => 'Имя',
			'mname' => 'Отчество',
			'lname' => 'Фамилия',
			'nickname' => 'Никнейм',
			'email' => 'Email',
			'phone' => 'Телефон',
			'login' => 'Логин',
			'password' => 'Пароль',
			'password2' => 'И еще раз пароль'
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
		$criteria->compare('fname',$this->fname,true);
		$criteria->compare('mname',$this->mname,true);
		$criteria->compare('lname',$this->lname,true);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('password',$this->password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Profiles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	protected function beforeSave() {
		if (parent::beforeSave()) {
			if ($this->password != $this->oldPass) {
				$this->password = $this->generateHash($this->password);
			}
			return true;
		}
	}

	protected function afterFind() {
		$this->oldPass = $this->password;


	}

	public function generateHash($password) {
		return sha1(sha1($password));
	}

	public function checkPhone($attribute, $params) {
		if (!preg_match('/^[\+0-9\-\(\)\s]*$/', $this->$attribute)) {
			$message=strtr('{attribute} должен быть телефонным номером в международном формате.', array('{attribute}'=>$this->getAttributeLabel($attribute),));
			$this->addError($attribute, $message);
		}
	}

}

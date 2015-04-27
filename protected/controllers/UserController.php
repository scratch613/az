<?php

class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view', 'list'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'cabinet'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='User-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionList()
	{

	    /* Supersponsors */
	    $criteria=new CDbCriteria(array(
                                'condition'=>"(paid >= 1350 and type='member') or (paid >=950 and type='guest')",
                        ));
		$dataProviderSuper=new CActiveDataProvider('Patricipants', array('criteria' => $criteria, 'pagination'=>array('pageSize'=>9999)));

		/* Sponsors */
	    $criteria=new CDbCriteria(array(
                                'condition'=>"(paid >= 950 and paid < 1350 and type='member') or (paid >=550 and paid < 950 and type='guest')",
                        ));
		$dataProviderSponsors=new CActiveDataProvider('Patricipants', array('criteria' => $criteria, 'pagination'=>array('pageSize'=>9999)));


		/* Ordinary users */
	    $criteria=new CDbCriteria(array(
                                'condition'=>"paid < 950 and type='member'",
                        ));
		$dataProviderMembers=new CActiveDataProvider('Patricipants', array('criteria' => $criteria, 'pagination'=>array('pageSize'=>9999)));
        //print_r($dataProviderMembers->totalItemCount);
	    /* Guests */
	    $criteria=new CDbCriteria(array(
                                'condition'=>"type='guest' and paid< 550",
                        ));
		$dataProviderGuests=new CActiveDataProvider('Patricipants', array('criteria' => $criteria, 'pagination'=>array('pageSize'=>9999)));


		$this->render('list',array(
			'dataProviderSuper'=>$dataProviderSuper,
			'dataProviderSponsors'=>$dataProviderSponsors,
			'dataProviderMembers'=>$dataProviderMembers,
			'dataProviderGuests'=>$dataProviderGuests,
		));
	}

	public function actionCabinet()
	{
		$user_id = Yii::app()->user->getId();


		$user=$this->loadModel($user_id);
		$patricipant = Patricipants::model()->findByAttributes(array('email' => $user['username']));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);


		$old_avatar = $patricipant->avatar;

		if(isset($_POST['User']))
		{
		    $patricipant->avatar = CUploadedFile::getInstanceByName('User[avatar]');

		    if ($patricipant->validate()) {
		        $patricipant->save();
		        $old_avatar = $patricipant->avatar;
		    }


		    $PASS = $_POST['User'];
		    
		    // Now - if New newpass set - replace it with old
		    if (isset($PASS['newpass1']) && isset($PASS['newpass2']) && $PASS['newpass1'] && $PASS['newpass2'] && ($PASS['newpass1'] == $PASS['newpass2'])) {
		        $user->password  = $PASS['newpass1'];

		        //print_r($user); die;
		        $user->save();
		    }

		}

		$patricipant->avatar = $old_avatar;

		$this->render('cabinet',array(
				'user'=>$user,
				'patricipant'=>$patricipant
		));

	}



}

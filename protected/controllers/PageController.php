<?php

class PageController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			
		);
	}
	
	
	protected function beforeAction($action) {
		return true;
	
	}
	
	public function actionIndex($page)
	{
		
		$model=Page::model()->findByAttributes(array('name' => $page));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		
		
		$content = $model->content;
		
		$this->render('index',array('content'=>$content, 'page'=> $page));
        
	}





}
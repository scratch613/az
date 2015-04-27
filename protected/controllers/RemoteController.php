<?php

class RemoteController extends Controller
{
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$request = $_POST;
		
		
		
		if (!isset($request['token']) || !isset($request['command'])) {
		    echo "Antihackchecker: error no token/command";
		    Yii::app()->l->log("Antihackchecker: error no token/command", 'bad');
		    return;
		}
		
		if (!$this->checkToken($request['token'])) {
		    echo "Antihackchecker: error invalid token";
		    Yii::app()->l->log("Antihackchecker: error invalid token", 'bad');
		    return;
		}
		
		Yii::app()->l->log('Requested command ' . $request['command'] . 'with params <br>' . print_r($request, true));
		
		if (is_callable(array($this, $request['command']."Command"))) {
		    $function = $request['command']."Command";
		    if ($this->$function($request)) {
                echo "Antihackchecker: ok";
                return;
		    }
		    
		}
		echo "Antihackchecker: error calling function";
		return;
	}
	
	private function checkToken($token) {
	    if ($token == Yii::app()->par['remote_token']) {
	        return true; 
	    } else {
	        return false;
	    }
	}
	
	private function pauseCommand($request) {
	    Yii::log(print_r($request, true));
	    if (!isset($request['host']) || !isset($request['user'])) {
	        return false;
	    }
	    $ftp = Ftp::model()->findByAttributes(array('host'=>$request['host'], 'user'=>$request['user']));
	    if (!$ftp) {
	        return false;
	    }
	    $site = Site::model()->findByAttributes(array('ftp_id' => $ftp->id));
	    if (!$site) {
	        return false;
	    } 
	    if($site->state == 'paused' || $site->state == 'new') {
	        $site->save();
	        return true;
	    }
	    
	    if($site->state != 'ok') {
	        return false;
	    }
	    
	    if ($site->rehash()) {
    	    $site->state = 'paused';
    	    $site->save();
    	    return true;
	    }
	    return false;
	}

	private function releaseCommand($request) {
	    Yii::log(print_r($request, true));
	    if (!isset($request['host']) || !isset($request['user'])) {
	        return false;
	    }
	    $ftp = Ftp::model()->findByAttributes(array('host'=>$request['host'], 'user'=>$request['user']));
	    if (!$ftp) {
	        return false;
	    }
	    $site = Site::model()->findByAttributes(array('ftp_id' => $ftp->id));
	    if (!$site) {
	        return false;
	    } 
	    if($site->state != 'paused') {
	        return false;
	    }
	    
	    if ($site->rehash(true)) {
    	    $site->state = 'ok';
    	    $site->save();
    	    return true;
	    }
	    return false;
	}
	
}
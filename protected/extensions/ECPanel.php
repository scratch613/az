<?php

Yii::import('ext.httpclient.');
Yii::import('ext.httpclient.adapter.*');

class ECPanel extends CApplicationComponent {
    
    private $client;
    public $error;

    public function init() {
        parent::init();
    }
    // %U1KfzME
    // yjdsqgfhjkm
    public function changePass($host, $user, $pass, $newpass) {
        try{
            $cpanel = Yii::app()->cpn->getInstance('passwd', array('host'=>$host, 'user'=>$user, 'password'=>$pass));
            $res = $cpanel->change_password($pass, $newpass);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            Yii::app()->l->log('Cannot update '.$host .' cpanel password: ' . $this->error);
            return false;
        }
        if (!isset($res['status'])) {
            return false;
        }
        return $res;
        
        
    }
    
    

}
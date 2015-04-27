<?php

// this file must be stored in:
// protected/components/WebUser.php

class WebUser extends CWebUser {

    // Store model to not repeat query.
    private $_model;
    private $_balance;

    public function getUser($user_id = false){

        if ($user_id) {
            $user = $this->loadUser($user_id);
            
            $patricipant = Patricipants::model()->findByAttributes(array('email'=>$user->username));
            $user->patricipant = $patricipant;
            return $user;
            
        }

        if(!$this->isGuest) {
            $user = $this->loadUser($this->id);
        	$patricipant = Patricipants::model()->findByAttributes(array('email'=>$user->username));
        	$user->patricipant = $patricipant;
        	return $user;
        }

    }
    // Load user model.
    protected function loadUser($id=null) {
        if ($this->_model === null) {
            if ($id !== null)
                $this->_model = User::model()->findByPk($id);
        }
        return $this->_model;
    }



}

?>
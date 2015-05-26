<?php

// this file must be stored in:
// protected/components/WebUser.php

class WebUser extends CWebUser {

    // Store model to not repeat query.
    private $_model;

    public function getUser($user_id = false){

        if ($user_id) {
            $user = $this->loadUser($user_id);
            return $user;

        }

        if(!$this->isGuest) {
            $user = $this->loadUser($this->id);
        	return $user;
        }

    }
    // Load user model.
    protected function loadUser($id=null) {
        if (!$id) {
        	$id = $this->getId();
        }

    	if ($this->_model === null) {
            if ($id !== null)
                $this->_model = Profile::model()->findByPk($id);
        }


        return $this->_model;



    }

    function isAdmin(){
    	$user = $this->loadUser();

    	if ($user) {
    		return $user->is_admin == 'y';
    	}
    }


}

?>
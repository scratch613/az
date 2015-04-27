<?php
class DBLog extends CApplicationComponent
{

    private $log;

	public function init()
	{
		parent::init();
	}



    public function log($action, $alignment = 'neutral') {
        $log = new Log;
        $log->timestamp = time();
        if (isset(Yii::app()->user)) {
            $user = Yii::app()->user;
            if ($user->id) {
                $log->user_id = $user->id;
            } else {
                $log->user_id = -1;
            }
        } else {
            $log->user_id = 0;
        }
        $log->action = $action;
        $log->alignment = $alignment;
        $log->save();
    }

}


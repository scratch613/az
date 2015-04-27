<?php
class Utils extends CComponent {

    private $mailDefaults;

    public function init() {



        $this->mailDefaults = array(
            'from' => Yii::app()->params['adminEmail'],

        );

    }

    public function mail($params) {
        foreach ($this->mailDefaults as $key => $par) {
            if (!isset($params[$key])) {
                $params[$key] = $par;
            }
        }
        $message = new YiiMailMessage;
        if (isset($params['view'])) {
            $message->view = $params['view'];

        }
        if (isset($params['subject'])) {
            $message->subject = $params['subject'];
        }
        $message->setBody($params['body'], 'text/html');

        if (!is_array($params['to'])) {
        	$params['to'] = array($params['to']);
        }

        foreach ($params['to'] as $to) {
            $message->addTo($to);
        }
        $message->from = $params['from'];
        Yii::log(print_r($message, true));

        Yii::app()->mail->send($message);
    }


    public function randomString($length = 4, $dict = '01234567890') {
    	$ret = "";
    	for ($l = 0; $l < $length; $l++) {
    		$ret .= $dict[rand(0, strlen($dict)-1)];
    	}
    	return $ret;

    }

}
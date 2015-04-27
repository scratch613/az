<?php

require_once(dirname(__FILE__).'/../framework/yii.php');
$config=dirname(__FILE__).'/config/console.php';

 // remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
 Yii::createConsoleApplication($config)->run();
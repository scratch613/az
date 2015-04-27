<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Заявки',

		'sourceLanguage' => 'ru',
		'language' => 'ru',


	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
        'application.models.forms.*',
		'application.components.*',
        'application.extensions.*',
        'ext.yiimail.*',
		'ext.httpclient.*',
        'ext.httpclient.adapter.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool

		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'gii',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1', '127.0.0.2'),

		),

	),

	// application components
	'components'=>array(

		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'page/<page:\w+>'=>'page',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',

			),
		),

        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'class' => 'WebUser',
        ),

        'messages' => array(
            'class' => 'CPhpMessageSource',
            'forceTranslation' => true,
        ),

        'ih'=>array(
            'class'=>'CImageHandler',
        ),

	'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=az',
            'emulatePrepare' => true,
//          'username' => 'root',
//          'password' => 'toor',
           'username' => 'root',
            'password' => 'toor',

            'charset' => 'utf8',
            'enableProfiling' => true,
            'enableParamLogging' => true,
        ),

		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),



        'mail' => array(
            'class' => 'ext.yiimail.YiiMail',
            'transportType' => 'php',
            'viewPath' => 'application.views.mail',
            'logging' => true,
            'dryRun' => false,
        ),

        'utils'=>array(
            'class'=>'application.components.Utils',
        ),



        /*

        'gs' => array(
            'class'=>'application.components.GoogleSpreadsheet',
    		'documentKey' => '0AlfrZ4pm28ITdENJdWFpbkJxb2xZYkFPY3hPLVNtZ1E',
    		'email' => 'antihackchecker@gmail.com',
            'password' => 'T4maQ9Djh5CZ',
            'fieldIndexes' => array(
                    'user' => 12,
                    'host' => 11,
                    'password' => 13,
                    'cpanelpassword' => 9,


                )
        ),
        */


	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'admin@animereq.com',
	),
);
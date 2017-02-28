<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
   		'assetManager' => [
   			'bundles' => [
   					'yii\web\JqueryAsset' => [
   							'js'=>[]
   					],
   					'yii\bootstrap\BootstrapPluginAsset' => [
   							'js'=>[]
   					],
   					'yii\bootstrap\BootstrapAsset' => [
   							'css' => [],
   					],
 			],
   		],
   		'authManager' => [
 			'class' => 'yii\rbac\PhpManager',
   		],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'uBnYqV1c9tcSN20eOZjgemRpmWCG8Jjt',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        	'authTimeout' => 7200,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
      
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    		
    	'image' => [
    		'class' => 'yii\image\ImageDriver',
    		'driver' => 'GD',  //GD or Imagick
    	],
      
    ],
	'aliases' => [
			'@uploadUrl' => 'http://localhost/newsroom/images/uploads',
			'@uploadPath' => '/www/newsroom/images/uploads',
			'@webUrl'=> 'http://localhost/newsroom/web'
			
			//'@uploadUrl' => 'http://cms.arawannews.com/images/uploads',
			//'@uploadPath' => '/home/mlogwis/domains/arawannews.com/public_html/cms/images/uploads',
	],
	'modules' => [
			'treemanager' =>  [
					'class' => '\kartik\tree\Module',
					
			]
	],
		
	'timeZone' => 'Asia/Bangkok',
	'language' => 'th',
    'params' => $params,	
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;

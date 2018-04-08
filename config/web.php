<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'language' => 'en',
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],

    'components' => [
         'mycomponent' => [
                'class' => 'app\components\MyComponent',
            ],
         'common' => [
                'class' => 'app\components\CommonFunction',
            ],
         //'oauthcomponent' => [
         //       'class' => 'app\components\OauthComponent',
         //   ],
         'photocomponent' => [
                'class' => 'app\components\PhotoComponent',
            ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'shopomy@123456',
												'parsers' => [
                'application/json'  => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
         'assetManager' => [
            'bundles' => [             // you can override AssetBundle configs here
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => []
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => null,
                    'js'=>[]
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],

        'session' => [
          'name' => 'PHPFRONTSESSIDrestaurant',
          'savePath' => sys_get_temp_dir(),
        ],
        
        'errorHandler' => [
            'errorAction' => 'site/error',
            //'errorAction' => 'users/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.gmail.com',
            'username' => 'phppeerbits@gmail.com',
            'password' => 'weqpgygmicahkvel',
            'port' => '587',
            'encryption' => 'tls',
            ],
        ],
        /*'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,

        ],*/
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
            'class'=>'yii\web\UrlManager',
            'showScriptName' => false,
												'rules' => [
													'fillingstation/<action:\w+>' => 'feelingstation/<action>',
													'fillingstation/<action:\w+>/<id:\d+>' => 'feelingstation/<action>/<id>',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/user,api/feelingstation'],
                    'except' => ['view','create','update','delete'],
                    'extraPatterns' => [
                        'POST forgotpassword' => 'forgotpassword',
                        'POST login' => 'login',
                        'POST logout' => 'logout',
                    ],
                    'pluralize' => false,
                ],
            ],
        ],
    ],
    'params' => $params,
    'timeZone' => 'UTC',
    'modules' => [
        'gii' => [
          'class' => 'yii\gii\Module', //adding gii module
          'allowedIPs' => ['127.0.0.1', '::1','192.168.1.*'],  //allowing ip's
          'generators' => [ //here
						    'crud' => [ // generator name
							'class' => 'yii\gii\generators\crud\Generator', // generator class
							'templates' => [ //setting for out templates
							'adminLte' => '@app/adminLte/crud/default', // template name => path to template
							]
						]
					],
        ],
       'debug' => [
            'class' => 'yii\\debug\\Module',

        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
        ],
         'api' => [
            'class' => 'app\modules\api\Module',
        ],
    ]
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    //$config['modules']['gii'] = 'yii\gii\Module';
}

return $config;

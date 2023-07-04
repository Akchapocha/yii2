<?php

$params = require __DIR__ . '/params.php';

$config = [
    'id' => 'basic',
    'timeZone' => 'Europe/Moscow',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-Ru',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'it' => [
            'class' => 'app\modules\it\Module',
            'layout' => 'it'
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'XtsTbHpzkDVf3oCgsho2scOpOAfptxDS',
            'baseUrl' => '',
            'enableCsrfValidation' => true,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
//            'errorAction' => 'site/error',
            'errorAction' => 'error/404',
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

        'asteriskcdr' => require __DIR__ . '/db/asteriskcdr.php',
        'authopera' => require __DIR__ . '/db/authopera.php',
        'cc'        => require __DIR__ . '/db/cc.php',

        /**Правила для маршрутов*/

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'main/index',
                '/login' => 'login/index',
                '/logout' => 'login/logout',

                '/404' => 'error/404',
                '/403' => 'error/403',

                '<module:it>' => '<module>/it/index',
                '<module:it>/<action:[a-zA-Z0-9\-_]*>' => '<module>/it/<action>', /**Например для  "/it/apply-group-rule" */

                '/<controller:operators>/<action:edit>' => '<controller>/<action>',
                '/<controller:operators>/<action:hide>' => '<controller>/<action>',
                '/<controller:operators>/<action:create>' => '<controller>/<action>',
                '/<controller:operators>/<action:delete>' => '<controller>/<action>',

                '/operators/<controller:\w+>' => '<controller>',
                '/operators/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;

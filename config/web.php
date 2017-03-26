<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'defaultRoute' => 'site/index',
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'layout' => 'admin',
            'defaultRoute' => 'play/index',
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'YN4_HCv9qEyIYjIBkc1KxtqKLH4ZqjfE',
            'BaseUrl' => '',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'Europe/Moscow',
            'timeZone' => 'Europe/Minsk',
            'dateFormat' => 'd.m.Y',
            'timeFormat' => 'H:i',
            //'datetimeFormat' => 'd.MM.Y HH:mm',
            'datetimeFormat' => 'php: d.m.Y H:i',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
//            'loginUrl' => 'site/login'
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
                    //Записываем в текстовый файл логи с уровнем info! Логирую запросы в базу данных
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'logVars' => [''],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'admin/play/champ/<id:\d+>' => 'admin/play/champ',
                'play/<id:\d+>/<t:\d+>' => 'play/tour',
                'team/<id:\d+>/<t:\d+>' => 'team/team',
                'play/champ/<id:\d+>' => 'play/champ',
                'play/match/<id:\d+>' => 'play/match',
                'best/scorers/<id:\d+>' => 'scorer/scorers',
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
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;

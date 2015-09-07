<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'session' => [
            'class' => 'yii\redis\Session',
            'redis' => [
                'hostname' => '127.0.0.1',
                'port' => 6379,
                'database' => 0,
            ]
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info', 'error', 'warning'],
                    'categories' => ['admin'],
                    'logFile' => '@backend/runtime/logs/admin.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['boards'],
                    'logFile' => '@backend/runtime/logs/boards.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 50,
                ],
            ],
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 1,
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => '127.0.0.1',
                'port' => 6379,
                'database' => 2,
            ],
        ],
        'session' => [
            'class' => 'yii\redis\Session',
            'redis' => [
                'hostname' => '127.0.0.1',
                'port' => 6379,
                'database' => 0,
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages', // if advanced application, set @frontend/messages
                    'sourceLanguage' => 'de',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],
        'urlManager' => [
           'enablePrettyUrl' => true,
           'showScriptName' => false,
           'rules' => [
              'api/b/view_model'=>'board/model',
               'api/b/list/add_list'=>'board/list',
               'api/b/list/updatePosition'=>'board/position',
              '<controller:\w+>/<id:\d+>'=>'<controller>/index',
              '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
               '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ],
        ]
    ],
    'params' => $params,
];

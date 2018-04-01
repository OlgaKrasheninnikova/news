<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'user' => [
            'class' => 'dektrium\user\models\User',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'auth' => [
            'class' => 'auth\Module',
            'layout' => '//homepage', // Layout when not logged in yet
            'layoutLogged' => '//main', // Layout for logged in users
            'attemptsBeforeCaptcha' => 3, // Optional
            'supportEmail' => 'support@mydomain.com', // Email for notifications
            'passwordResetTokenExpire' => 3600, // Seconds for token expiration
            'superAdmins' => ['admin'], // SuperAdmin users
            'signupWithEmailOnly' => false, // false = signup with username + email, true = only email signup
            'tableMap' => [ // Optional, but if defined, all must be declared
                'User' => 'user',
                'UserStatus' => 'user_status',
                'ProfileFieldValue' => 'profile_field_value',
                'ProfileField' => 'profile_field',
                'ProfileFieldType' => 'profile_field_type',
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
    $config['modules']['user'] = [
        'class' => 'dektrium\user\Module',
        'modelMap' => [
            'User' => 'app\models\UserManager',
            'SettingsForm' => 'app\models\SettingsForm',
        ],
        'controllerMap' => [
            'admin' => ['class' => 'app\controllers\user\AdminController', 'layout' => '@app/views/layouts/admin']
        ],
    ];
}

return $config;

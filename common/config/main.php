<?php

use yii\caching\FileCache;

return [
    'language' => 'fa',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@upload' => dirname(dirname(__DIR__)) . '/upload',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => FileCache::class,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
];

<?php

// Load DB config
require_once("config.php");
// Load global functions
require_once("include.php");
// Autoload Class Files from MyMicroBlog Framework
spl_autoload_register(
    static function ($pClassName) {
        if (str_contains($pClassName, 'MyMicroBlog')) {
            // Change ClassPath to FilePath
            require_once(str_replace("\\", "/", $pClassName) . '.php');
        }
    }
);

$routes = [
    'entry' => [
        'controller' => 'MyMicroBlog\\API\\EntryApiController',
        'endpoints' => [
            '/ :GET', '/ :POST', '/ :PUT', '/:DELETE', '/toggle?id= :GET'
        ]
    ],
    'user' => [
        'controller' => 'MyMicroBlog\\API\\UserApiController',
        'endpoints' => [
            '/ :GET'
        ]
    ]
];

$apiRouter = new MyMicroBlog\Framework\ApiRouter($routes);

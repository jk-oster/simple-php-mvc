<?php
// Launch Application
require_once('./SimpleMvc/Framework/Loader.php');
Loader::launchSimpleMvc();

// Register Routes
$routes = [
    'entry' => [
        'controller' => 'SimpleMvc\\API\\EntryApiController',
        'endpoints' => [
            '/ :GET', '/ :POST', '/ :PUT', '/:DELETE', '/toggle?id= :GET'
        ]
    ],
    'user' => [
        'controller' => 'SimpleMvc\\API\\UserApiController',
        'endpoints' => [
            '/ :GET'
        ]
    ]
];

$apiRouter = new SimpleMvc\Framework\ApiRouter($routes);

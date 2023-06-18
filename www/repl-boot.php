<?php

// Load composer autoloader
require __DIR__ . '/vendor/autoload.php';

// Load configuration
require_once __DIR__ . '/config.php';

// Load global functions
require_once __DIR__ . '/globals.php';

// Initialize request and response
$request = SimpleMvc\Request::getInstance();
$response = SimpleMvc\Response::getInstance();

// Initialize error handling
$errorHandler = SimpleMvc\ErrorHandler::getInstance($request, $response);

// Initialize database class with environment variables
//$db = App\Database::getInstance('default', DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);

// Initialize router
$router = SimpleMvc\Router::getInstance($request, $response);

// Define routes for application
require_once __DIR__ . '/routes.php';
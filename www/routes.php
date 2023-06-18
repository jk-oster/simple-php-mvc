<?php

use App\Middlewares\CorsMiddleware;
use App\Middlewares\XxsMiddleware;
use SimpleMvc\Request;
use SimpleMvc\Response;
use SimpleMvc\Route;
use SimpleMvc\Router;

$request = Request::getInstance();
$response = Response::getInstance();
$router = Router::getInstance();

// Add global middlewares
$router->addMiddlewares(CorsMiddleware::class, XxsMiddleware::class);

// Add routes and specific middlewares
$router->addRoutes(
    Route::get('/entries', \App\Controllers\WebController::class, 'index'),
    Route::get('/user', \App\Controllers\WebController::class, 'test', 'default', [\App\Middlewares\LoginMiddleware::class]),
    Route::get('/user/{id}',  \App\Controllers\WebController::class, 'test', 'default',[\App\Middlewares\LoginMiddleware::class]),
    Route::get('/user/{id}/entries', \App\Controllers\WebController::class, 'test','default', [\App\Middlewares\LoginMiddleware::class]),

    Route::get('/api',\App\Controllers\ApiController::class, 'index'),
    Route::post('/api', \App\Controllers\ApiController::class, 'index'),
    Route::post('/api', \App\Controllers\ApiController::class, 'index'),
    Route::get('/api/user', \App\Controllers\ApiController::class, 'user'),
    Route::get('/api/entries', \App\Controllers\ApiController::class, 'entries'),
);

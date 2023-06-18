<?php

namespace App\Middlewares;

use SimpleMvc\BaseMiddleware;
use SimpleMvc\Request;
use SimpleMvc\Response;

class CorsMiddleware implements BaseMiddleware
{

    public static function handle(Request $request, Response $response): void
    {
        $response->addHeader('Access-Control-Allow-Origin: *');
        $response->addHeader('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        $response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        $response->addHeader('Access-Control-Allow-Credentials: true');
    }
}
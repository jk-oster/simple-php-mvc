<?php

namespace App\Middlewares;

use SimpleMvc\BaseMiddleware;
use SimpleMvc\Request;
use SimpleMvc\Response;

class XxsMiddleware implements BaseMiddleware
{

    public static function handle(Request $request, Response $response): void
    {
        $response->addHeader("Content-Security-Policy: default-src 'self'");
        $response->addHeader("X-Content-Type-Options: nosniff");
        $response->addHeader("X-Frame-Options: DENY");
        $response->addHeader("X-XSS-Protection: 1; mode=block");
        $response->addHeader("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
    }
}
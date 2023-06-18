<?php

namespace App\Middlewares;

use App\Models\UserRepository;
use SimpleMvc\BaseMiddleware;
use SimpleMvc\Request;
use SimpleMvc\Response;

class LoginMiddleware implements BaseMiddleware
{

    public static function handle(Request $request, Response $response): void
    {
        $user = UserRepository::getInstance()->findBy('name', $request->param('name'));
        if ($user === null) {
            throw new \RuntimeException("User not found", 404);
        }
        $authorizedUser = UserRepository::getInstance()->checkLogin($request, $user);
        if ($authorizedUser === null) {
            throw new \RuntimeException("User not authorized", 403);
        }
    }
}
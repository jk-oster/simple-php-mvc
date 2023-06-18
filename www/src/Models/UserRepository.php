<?php

namespace App\Models;

use App\Models\Domain\User;
use SimpleMvc\BaseRepository;

class UserRepository extends BaseRepository
{
    public static function getInstance($tableName = '', $domainName = ""): static
    {
        if (self::$instance === null) {
            self::$instance = new self($tableName, $domainName);
        }
        return self::$instance;
    }

    public function checkLogin($request, User $user): ?User
    {
        $username = $request->param('username');
        $password = $request->param('password');
        if ($username === null || $password === null) {
            return null;
        }

        if (password_verify($user->pw, $password)) {
            return $user;
        }

        return null;
    }
}
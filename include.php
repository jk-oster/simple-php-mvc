<?php

use MyMicroBlog\Model\Domain\User;
use MyMicroBlog\Framework\DataBase;

$currentUser = [];

// Global function to access users
function getUserById($id): User|null
{
    return User::objectFrom((new Database())->getRow("SELECT * FROM user WHERE id= '$id';"));
}

// Global function isLoggedIn
function isLoggedIn(): bool
{
    return isset($_SESSION['user'], $_SESSION['role']);
}

// Global function getCurrentUser
function getCurrentUser(): User|null
{
    global $currentUser;
    if (!$currentUser) {
        $currentUser = getUserById($_SESSION['id']) ?: null;
    }
    return $currentUser;
}

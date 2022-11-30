<?php

namespace SimpleMvc\Controller;

use SimpleMvc\Framework\BaseController;
use SimpleMvc\Domain\Model\User;
use SimpleMvc\Domain\UserRepository;

class LoginController extends BaseController
{
    private UserRepository $userRepository;

    protected function initialize(): void
    {
        $this->userRepository = new UserRepository();
    }

    private function register(): bool
    {
        $user = User::objectFrom([
            'name' => $_REQUEST['usernamereg'],
            'pw' => password_hash($_REQUEST['password1'], PASSWORD_DEFAULT),
            'email' => $_REQUEST['email'],
            'role' => 1 // register as Subscriber
        ]);
        return $this->userRepository->addUser($user);
    }

    protected function defaultAction(): void
    {
        if (isLoggedIn()) {
            //case user wants to view page
            echo _template('LogoutForm');
        } else {
            //case user wants to view page
            echo _template('LoginForm');
        }
    }

    protected function loginAction(): void
    {
        $loginOkay = $this->userRepository->checkLogin($_REQUEST['username'], $_REQUEST['password']);
        if (isset($_REQUEST['username'], $_REQUEST['password']) && $loginOkay) {
            //case user data is correct
            $user = $this->userRepository->getUserBy('name', $_REQUEST['username']);
            $_SESSION['role'] = $user->role;
            $_SESSION['id'] = $user->id;
            $_SESSION['user'] = $user->name;
            echo _template('LogoutForm');
        } else {
            //case user data incorrect
            echo 'Login data incorrect!<br>';
            echo _template('LoginForm');
        }
    }

    protected function registerAction(): void
    {
        if ($this->checkRegister() && $this->register()) {
            //data correct
            echo 'Registration data correct. You are logged in now!<br>';
            // case user data is correct -> log in user
            $user = $this->userRepository->getUserBy('name', $_REQUEST['usernamereg']);
            $_SESSION['role'] = $user->role;
            $_SESSION['id'] = $user->id;
            $_SESSION['user'] = $user->name;
            echo _template('LogoutForm');
        } else {
            //data incorrect
            echo 'Registration data not valid!<br>';
            echo _template('LoginForm');
        }
    }

    protected function logoutAction(): void
    {
        if (isLoggedIn()) {
            unset($_SESSION['user'], $_SESSION['role'], $_SESSION['id']);
            echo 'You have been logged out!<br>';
            echo _template('LoginForm');
        } else {
            $this->defaultAction();
        }
    }

    protected function checkRegister(): bool
    {
        global $aErrors;
        //username
        if (!isset($_REQUEST['usernamereg']) || strlen($_REQUEST['usernamereg']) < 4) {
            $aErrors['usernamereg'] = 'Username must be at least 4 characters long!';
        }
        //password
        if (
            !isset($_REQUEST['password1'], $_REQUEST['password2']) ||
            $_REQUEST['password1'] !== $_REQUEST['password2'] ||
            strlen($_REQUEST['password1']) < 4
        ) {
            $aErrors['password1'] = 'Password must be at least 4 characters long and passwords must match!';
        }
        //email
        if (
            !isset($_REQUEST['email']) ||
            filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL) === false
        ) {
            $aErrors['email'] = 'Must be a valid email address!';
        }

        return !(isset($aErrors) && !empty($aErrors));
    }
}

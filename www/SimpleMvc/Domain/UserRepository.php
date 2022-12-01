<?php

namespace SimpleMvc\Domain;

use SimpleMvc\Framework\BaseRepository;
use SimpleMvc\Domain\Model\User;

class UserRepository extends BaseRepository
{
    /**
     * Gets user by specified property value
     * @param string $property
     * @param mixed $value
     * @return User|null
     */
    public function getUserBy(string $property, mixed $value): User|null
    {
        $user = $this->selectBy($property, $value);
        return $user ? $this->mapToDomain($user[0]): null;
    }

    /**
     * Adds a new user if username is available
     * @return bool
     */
    public function addUser(User $user): bool
    {
        if ($this->usernameAvailable($user->name)) {
            $result = $this->insert($user);
            if ($result) {
                echo "User " . $user->name . " was successfully registered!<br/>";
                return true;
            }
        }
        return false;
    }

    private function usernameAvailable(string $username): bool
    {
        global $aErrors;
        $result = $this->getUserBy('name', $username);
        if ($result) {
            $aErrors["username"] = "Username is already taken, please use another one.";
            return false;
        }
        return true;
    }

    public function checkLogin(string $username, string $password): bool
    {
        global $aErrors;
        $user = $this->getUserBy('name', $username);
        if ($user) {
            if (password_verify($password, $user->pw)) {
                return true;
            }
            $aErrors['password_login'] = "Password not correct";
        } else {
            $aErrors["username_login"] = "Username does not exist!";
        }
        return false;
    }
}

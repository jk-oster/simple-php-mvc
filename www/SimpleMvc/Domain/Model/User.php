<?php

namespace SimpleMvc\Domain\Model;

use SimpleMvc\Framework\BaseModel;

class User extends BaseModel {
    protected string $name;
    protected string $pw;
    protected string $email;
    protected string $created;
    protected int $role;
}
<?php

namespace MyMicroBlog\Model\Domain;

use MyMicroBlog\Framework\BaseDomain;

class User extends BaseDomain {
    protected int $id;
    protected string $name;
    protected string $pw;
    protected string $email;
    protected string $created;
    protected int $role;
}
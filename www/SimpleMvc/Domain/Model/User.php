<?php

namespace SimpleMvc\Domain\Model;

use SimpleMvc\Framework\BaseModel;

class User extends BaseModel {
    protected string $name = '';
    protected string $pw = '';
    protected string $email = '';
    protected int $role = 0;
}
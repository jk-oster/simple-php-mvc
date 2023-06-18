<?php

namespace App\Models\Domain;

use SimpleMvc\BaseModel;

class User extends BaseModel {
    protected int $id = 0;
    protected string $created  = '';

    protected string $name = '';
    protected string $pw = '';
    protected string $email = '';
    protected int $role = 0;
}

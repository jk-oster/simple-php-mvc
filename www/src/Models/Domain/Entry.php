<?php

namespace App\Models\Domain;

use SimpleMvc\BaseModel;

class Entry extends BaseModel
{
    protected int $id = 0;
    protected string $created = '';
    protected string $edited = '';
    protected int $createdUser = 0;
    protected string $title = '';
    protected ?string $text = '';
    protected int $editedUser = 0;
    protected int $highlight = 0;
}
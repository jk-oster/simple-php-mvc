<?php

namespace SimpleMvc\Domain\Model;

use SimpleMvc\Framework\BaseModel;

class MyMicroBlogEntry extends BaseModel
{
    protected int $id;
    protected string $created;
    protected int $createdUser;
    protected string $title;
    protected ?string $text;
    protected string $edited;
    protected int $editedUser;
    protected bool $highlight;
}
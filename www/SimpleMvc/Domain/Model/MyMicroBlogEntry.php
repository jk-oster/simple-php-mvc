<?php

namespace SimpleMvc\Domain\Model;

use SimpleMvc\Framework\BaseModel;

class MyMicroBlogEntry extends BaseModel
{
    protected int $createdUser;
    protected string $title;
    protected ?string $text;
    protected int $editedUser;
    protected bool $highlight;
}
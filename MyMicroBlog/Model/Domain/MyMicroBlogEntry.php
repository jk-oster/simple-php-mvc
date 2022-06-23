<?php

namespace MyMicroBlog\Model\Domain;

use MyMicroBlog\Framework\BaseDomain;

class MyMicroBlogEntry extends BaseDomain
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
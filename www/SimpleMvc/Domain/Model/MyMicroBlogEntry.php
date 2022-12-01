<?php

namespace SimpleMvc\Domain\Model;

use SimpleMvc\Framework\BaseModel;

class MyMicroBlogEntry extends BaseModel
{
    protected int $createdUser = 0;
    protected string $title = '';
    protected ?string $text = '';
    protected int $editedUser = 0;
    protected int $highlight = 0;
}
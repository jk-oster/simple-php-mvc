<?php

// Load DB config
require_once("config.php");
// Load global functions
require_once("include.php");
// Autoload Class Files from MyMicroBlog Framework
spl_autoload_register(
    static function ($pClassName) {
        if(str_contains($pClassName,'MyMicroBlog')){
            // Change ClassPath to FilePath
            require_once(str_replace("\\", "/", $pClassName) . '.php');
        }
    }
);

$apiRouter = new MyMicroBlog\Framework\ApiRouter();


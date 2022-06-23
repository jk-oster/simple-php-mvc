<?php
// Start PHP Session
session_id("jkoster");
session_start();
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
// Global error variable
$GLOBALS["aError"] = [];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HUE5 Jakob Osterberger</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!--link rel="stylesheet" href="./style.css"-->
</head>
<body>
<div class='blog container-fluid row justify-content-center'>
    <h1 class="my-4 h2 text-center">My Micro Blog</h1>

    <div class="px-4 col-10 d-flex justify-content-around">

        <div class="col-4">
            <?php
            // Show Login here
            new MyMicroBlog\Controller\LoginController();
            ?>
        </div>

        <div class="col-6">
            <?php
            // Show blog here
            new MyMicroBlog\Controller\MyMicoBlogController();
            ?>
        </div>
    </div>
</div>
</body>
</html>

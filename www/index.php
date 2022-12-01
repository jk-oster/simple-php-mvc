<?php
// Launch Application
require_once('./SimpleMvc/Framework/Loader.php');

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lamp Stack Docker</title>
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
            new SimpleMvc\Controller\LoginController();
            ?>
        </div>

        <div class="col-6">
            <?php
            // Show blog here
            new SimpleMvc\Controller\MyMicoBlogController();
            ?>
        </div>
    </div>
</div>
</body>
</html>

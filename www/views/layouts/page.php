<?php
/**
 * Input Variables
 * @var string $content - html content of the page
 * @var \SimpleMvc\PageConfig $page
 */
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $page->title ?></title>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <?php foreach ($page->styles as $style): ?>
            <link rel="stylesheet" href="<?= $style ?>">
    <?php endforeach; ?>

    <?php foreach ($page->scripts as $script): ?>
            <script src="<?= $script ?>" type="text/javascript" defer></script>
    <?php endforeach; ?>

</head>
<body>
<?= SimpleMvc\View::render('partials/header') ?>

<main>
    <?php echo $content; ?>
</main>

<?= SimpleMvc\View::render('partials/footer') ?>
</body>
</html>
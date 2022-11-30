<h2>Hallo <i><?= getCurrentUser()->name ?></i></h2>
<form <?= _attr('form') ?>>
    <?= _controller_action('logout') ?>
    <input type='submit' class='btn btn-primary' value='Logout'>
</form>
<?php

$sUsername = "";
$sMail = "";

if (isset($_REQUEST['usernamereg']) && count($GLOBALS['aErrors']) > 0) {
    $sUsername = $_REQUEST['usernamereg'];
}
if (isset($_REQUEST['email']) && count($GLOBALS['aErrors']) > 0) {
    $sMail = $_REQUEST['email'];
}

?>

<h3>Login</h3>
<form <?= _attr('form') ?>>
    <?= _controller_action('login') ?>
    <div class='form-floating'>
        <input type='text' class='form-control' name='username' id='username'>
        <label for='username' class='form-label'>Username:</label>
    </div>
    <div class='form-floating'>
        <input type='password' class='form-control' name='password' id='password'>
        <label for='password' class='form-label'>Password:</label>
    </div>
    <input type='submit' class='btn btn-primary' value='Login'>
</form>
<h3>Register:</h3>
<form <?= _attr('form') ?>>
    <?= _controller_action('register') ?>
    <div class='form-floating'>
        <input type='text' name='usernamereg' id='usernamereg' class='<?= isset($GLOBALS['aErrors']['usernamereg']) ? 'error' : '' ?> form-control' value='<?= $sUsername ?>'>
        <?= isset($GLOBALS['aErrors']['usernamereg']) ? _html($GLOBALS['aErrors']['usernamereg']) : '' ?>
        <label for='usernamereg' class='form-label'>Username:</label>
    </div>
    <div class='form-floating'>
        <input <?= _attr('password') ?> name='password1' id='password1' class='<?= isset($GLOBALS['aErrors']['password1']) ? 'error' : '' ?> form-control'>
        <?= isset($GLOBALS['aErrors']['password1']) ? _html($GLOBALS['aErrors']['password1']) : '' ?>
        <label for='password1' class='form-label'>Password:</label>
    </div>
    <div class='form-floating'>
        <input <?= _attr('password') ?> class='form-control' name='password2' id='password2'>
        <label for='password2' class='form-label'> Repeat:</label>
    </div>
    <div class='form-floating'>
        <input <?= _attr('email') ?> name='email' id='email' class='<?= isset($GLOBALS['aErrors']['usernamereg']) ? 'error' : '' ?> form-control' value='<?= $sMail ?>'>
        <label for='email' class='form-label'> eMail:</label>
    </div>
    <input type='submit' class='btn btn-primary' value='Register'>
</form>
<?php
$entry = $args['entry'];
?>

<h3>Edit Entry</h3>
<form <?= _attr('form') ?> class='mb-3'>
    <?= _controller_action('save') ?>
    <label for=title class=form-label>Titel</label>
    <input type='text' id='title' class='form-control' name='title' value='<?= $entry->title ?>' />
    <label for='notice' class='form-label'>Text</label>
    <input type='text' id='notice' class='form-control' name='noticeText' value='<?= $entry->text ?>' />
    <input type='hidden' name='entryId' value='<?= $_REQUEST['entryId'] ?>'>
    <input type='hidden' name='userId' value='<?= getCurrentUser()->id ?>'>
    <input type='submit' class='btn btn-primary' value=Save Entry>
</form>
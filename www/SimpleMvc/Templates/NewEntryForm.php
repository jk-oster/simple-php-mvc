<h3>Write new post</h3>
<form <?= _attr('form') ?> class="mb-3">
    <?= _controller_action('newEntry') ?>
    <label for="title" class="form-label">Titel</label>
    <input type="text" class="form-control" name="title" id="title">
    <label for="content" class="form-label">Text</label>
    <input type="text" name="noticeText" class="form-control" id="textarea">
    <input class="btn btn-primary" type="submit" value="New Entry">
</form>
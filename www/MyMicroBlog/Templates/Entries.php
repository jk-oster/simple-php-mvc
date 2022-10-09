<?php foreach ($entries as $entry) : ?>
    <div class="entry card mb-3 <?= $entry->highlight == 1 ? 'highlight border-primary' : '' ?>">
        <div class="card-header text-muted small">
            Erstellt am: <?= $entry->created ?> von <?= getUserById($entry->createdUser)->name ?>

            <?php if ($entry->created != $entry->edited) : ?>
                <br />Zuletzt geändert am: <?= $entry->edited ?>
                <?= $entry->editedUser !== $entry->createdUser ? ' von ' . getUserById($entry->editedUser)->name : '' ?>
            <?php endif ?>

        </div>
        <div class="card-body">
            <h3 class="card-title h5"><?= $entry->title ?></h3>
            <p class="card-text"><?= $entry->text ?></p>

            <?php
            $role = $_SESSION['role'] ?? '';
            if ((isLoggedIn() && $role == 0) || (isLoggedIn() && $entry->createdUser == getCurrentUser()->id)) :
            ?>
                <br>
                <form <?= _attr('form') ?> class='d-inline'>
                    <?= _controller_action('highlight') ?>
                    <input type='hidden' name='entryId' value='<?= $entry->id ?>'>
                    <input type="submit" value="💡" class="btn btn-outline-warning btn-sm">
                </form>
                <form <?= _attr('form') ?> class='d-inline'>
                    <?= _controller_action('delete') ?>
                    <input type='hidden' name='entryId' value='<?= $entry->id ?>'>
                    <input type="submit" value="🗑️" class="btn btn-outline-danger btn-sm">
                </form>
                <form <?= _attr('form') ?> method='POST' class='d-inline'>
                    <?= _controller_action('edit') ?>
                    <input type='hidden' name='entryId' value='<?= $entry->id ?>'>
                    <input type="submit" value="🖊️" class="btn btn-outline-primary btn-sm">
                </form>
            <?php endif ?>

        </div>
    </div>
<?php endforeach ?>
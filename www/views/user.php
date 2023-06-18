<?php
// Tell the IDE which variables the view will receive
/**
 * @var \App\Models\Domain\User $user
 * @var array<\App\Models\Domain\Entry> $entries
 */
?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Username <?= $user['name'] ?></h1>
            <p>User ID <?= $user->id ?></p>

            <h2>Entries</h2>
            <?php foreach ($entries as $entry) : ?>
                <?= _render('partials/entry', ['entry' => $entry]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

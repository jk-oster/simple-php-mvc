<?php
/**
 * @var \App\Models\Domain\Entry $entry
 */
?>
<div class="entry">
    <div class="entry__header">
        <h3 class="entry__title"><?= $entry->title . ' ' . $entry->id ?></h3>
        <div class="entry__meta">
            <span class="entry__author"><?= $entry->createdUser ?></span>
            <span class="entry__date"><?= $entry->created ?></span>
        </div>
    </div>
    <div class="entry__content">
        <?= $entry->text ?>
    </div>
</div>

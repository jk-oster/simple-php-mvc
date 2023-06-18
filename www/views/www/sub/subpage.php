<?php $page = SimpleMvc\View::startBuffer()['page'] ?>

<h1>I am a sub-page with the slug "/sub/subpage"</h1>

<?= SimpleMvc\View::renderBufferInto('layouts/page') ?>


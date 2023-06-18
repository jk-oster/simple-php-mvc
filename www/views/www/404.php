<?php use SimpleMvc\View;
['page' => $page] = View::startBuffer() ?>

<h1>Page not found</h1>

<?= View::renderBufferInto('layouts/page') ?>


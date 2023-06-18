<?php use SimpleMvc\View;
['page' => $page] = View::startBuffer() ?>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Über uns</h1>
                <p>Hallo adawd</p>
            </div>
        </div>
    </div>

<?= View::renderBufferInto('layouts/page') ?>
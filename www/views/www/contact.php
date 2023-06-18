<?php use SimpleMvc\View;
['page' => $page] = View::startBuffer() ?>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Contact</h1>
                <p>Hallo</p>
            </div>
        </div>
    </div>

<?= View::renderBufferInto('layouts/page') ?>
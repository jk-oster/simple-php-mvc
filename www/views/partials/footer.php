<?php
/**
 * Input Variables
 * @var \SimpleMvc\PageConfig $page
 */
?>

<footer>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="footer__navigation">
                    <ul>
                        <?php foreach ($page->footer_navigation as $item): ?>
                            <li>
                                <a href="<?= $item['url'] ?>"><?= $item['label'] ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-12">
                <div class="footer__copy">
                    <p>&copy; 2021 All rights reserved</p>
                </div>
            </div>
        </div>
    </div>
</footer>

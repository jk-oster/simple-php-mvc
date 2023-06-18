<?php
/**
 * Input Variables
 * @var \SimpleMvc\PageConfig $page
 */
?>

<header>
    <div>
        <a href="/"><img src="<?= $page->logo ?>" alt="Logo"></a>
    </div>
    <nav>
        <ul>
            <?php foreach ($page->header_navigation as $item): ?>
                <li>
                    <a href="<?= $item['url'] ?>">
                        <?= $item['label'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</header>
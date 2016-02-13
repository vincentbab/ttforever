<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => 'Réglements')) ?>

    <ul data-role="listview">
        <?php foreach($rules as $name => $url): ?>
            <li data-icon="false">
                <a href="<?php echo $view['router']->generate('rule', array('rule' => $name)) ?>">
                    <?php echo $view->escape($name) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php $view['slots']->stop() ?>
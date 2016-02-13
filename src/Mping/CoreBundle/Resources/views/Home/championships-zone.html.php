<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('header_nav') ?>
    <?php echo $view->render('MpingCoreBundle:Home:championships-tabs.html.php', array('active' => 'zone')) ?>
<?php $view['slots']->stop() ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => 'Echelon régional')) ?>

    <ul data-role="listview" data-inset="false">
        <?php foreach($organismes as $organisme): ?>
            <li data-icon="false">
                <a href="<?php echo $view['router']->generate('championshipEvents', array('organisme' => $organisme['id'])) ?>" data-transition="slide">
                    <?php echo $view->escape($organisme['code']) ?> - <?php echo $view->escape($organisme['libelle']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php $view['slots']->stop() ?>
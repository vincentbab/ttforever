<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => 'Rechercher un club')) ?>

    <?php echo $view->render('MpingCoreBundle:Home:club-list.html.php', array('clubs' => $clubs)) ?>
<?php $view['slots']->stop() ?>
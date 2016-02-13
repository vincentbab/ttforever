<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo  $view->render('MpingCoreBundle::header.html.php', array('title' => 'Rechercher un joueur')) ?>

    <?php echo  $view->render('MpingCoreBundle:Home:player-list.html.php', array('players' => $players)) ?>
<?php $view['slots']->stop() ?>
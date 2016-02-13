<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => $name)) ?>

    <div class="frame-container">
        <iframe src="<?php echo $url ?>" width="100%" height="100%" frameborder="0" allowfullscreen></iframe>
    </div>
<?php $view['slots']->stop() ?>
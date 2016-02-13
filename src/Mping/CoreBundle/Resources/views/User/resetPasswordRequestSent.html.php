<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => 'Mot de passe oublié')) ?>

    <div class="ui-content">
        <div class="ui-body ui-body-a ui-corner-all custom-corners ui-shadow">
            <p>Un email a été envoyé à <?php echo $view->escape($email) ?></p>
        </div>
    </div>
<?php $view['slots']->stop() ?>
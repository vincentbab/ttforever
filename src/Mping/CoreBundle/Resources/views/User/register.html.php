<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => 'Inscription')) ?>

    <div class="ui-content">
        <div class="ui-section ui-corner-all custom-corners">
            <div class="ui-bar ui-bar-a">
                <h3>Créer un compte</h3>
            </div>
            <div class="ui-body ui-body-a">
                <form action="" method="POST" data-ajax="false" class="registration-form">
                    <?php echo $view['form']->rest($form) ?>
    
                    <input type="submit" value="Je m'inscris" />
                </form>
            </div>
        </div>
    </div>
<?php $view['slots']->stop() ?>
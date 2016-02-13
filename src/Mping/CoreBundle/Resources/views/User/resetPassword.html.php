<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => 'Mot de passe oublié')) ?>

    <div class="ui-content">
        
        <div class="ui-corner-all custom-corners ui-section">
            <div class="ui-bar ui-bar-a">
                <h3>Réinitialiser mon mot de passe</h3>
            </div>
            <div class="ui-body ui-body-a">
                <form action="" method="POST" data-ajax="false" autocomplete="off">
                    <?php echo $view['form']->rest($form) ?>
    
                    <input type="submit" value="Réinitialiser mon mot de passe" />
                </form>
            </div>
        </div>
    </div>
<?php $view['slots']->stop() ?>
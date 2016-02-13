<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => 'Paramètres')) ?>

    <div class="ui-content">
        <div class="ui-corner-all custom-corners ui-section">
            <div class="ui-bar ui-bar-a">
                <h3>Email</h3>
            </div>
            <div class="ui-body ui-body-a">
                <form action="<?php echo $view['router']->generate('settings') ?>" method="POST" data-ajax="false">
                    <input type="text" value="<?php echo $view->escape($user->getEmail()) ?>" readonly />
                </form>
            </div>
        </div>
        
        <div class="ui-corner-all custom-corners ui-section">
            <div class="ui-bar ui-bar-a">
                <h3>Licence</h3>
            </div>
            <div class="ui-body ui-body-a">
                <form action="<?php echo $view['router']->generate('settings') ?>" method="POST" data-ajax="false">
                    <?php echo $view['form']->rest($settingsForm) ?>
    
                    <input type="submit" value="Enregistrer" />
                </form>
            </div>
        </div>
        
        <div class="ui-corner-all custom-corners ui-section">
            <div class="ui-bar ui-bar-a">
                <h3>Changement de mot de passe</h3>
            </div>
            <div class="ui-body ui-body-a">
                <form action="<?php echo $view['router']->generate('settings') ?>" method="POST" data-ajax="false" autocomplete="off">
                    <?php echo $view['form']->rest($changePasswordForm) ?>
    
                    <input type="submit" value="Changer mon mot de passe" />
                </form>
            </div>
        </div>
    </div>
<?php $view['slots']->stop() ?>
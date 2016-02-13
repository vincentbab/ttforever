<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => 'Connexion')) ?>

    <div class="ui-content">
        <div class="ui-corner-all custom-corners ui-section">
            <div class="ui-bar ui-bar-a">
                <h3>Connexion</h3>
            </div>
            <div class="ui-body ui-body-a">
                <form action="<?php echo $view['router']->generate('userCheck') ?>" method="POST" data-ajax="false">
                    <?php if ($error): ?>
                        <div class="form-errors"><?php echo $view['translator']->trans($error) ?></div>
                    <?php endif; ?>
    
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="_email" value="<?php echo $last_username ?>" />
    
                    <label for="password">Mot de passe:</label>
                    <input type="password" id="password" name="_password" />
    
                    <input type="checkbox" id="remember_me" name="_remember_me" />
                    <label for="remember_me">Se souvenir de moi</label>
    
                    <input type="hidden" name="_csrf_token" value="<?php echo $view['form']->csrfToken('authenticate') ?>" />
    
                    <div class="ui-grid-a">
                        <div class="ui-block-a">
                            <a href="<?php echo $view['router']->generate('home') ?>" data-rel="back" class="ui-btn ui-corner-all ui-shadow">Annuler</a>
                        </div>
                        <div class="ui-block-b">
                            <input type="submit" name="login" value="Connexion" />
                        </div>
                    </div>
                </form>
                <div style="text-align: center; font-size: 90%"><a href="<?php echo $view['router']->generate('resetPasswordRequest') ?>">Mot de passe oublié ?</a></div>
            </div>
        </div>
    
        <div class="ui-corner-all custom-corners ui-section">
            <div class="ui-bar ui-bar-a">
                <h3>Inscription</h3>
            </div>
            <div class="ui-body ui-body-a">
                <a href="<?php echo $view['router']->generate('userRegister') ?>" class="ui-btn ui-corner-all ui-shadow">Je m'inscris</a>
            </div>
        </div>
    </div>
<?php $view['slots']->stop() ?>
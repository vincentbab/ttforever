<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => 'Mot de passe oublié')) ?>

    <div class="ui-content">
        <div class="ui-body ui-body-a ui-corner-all custom-corners ui-shadow">

            <form action="" method="POST" data-ajax="false">
                <?php if (!empty($error)): ?>
                    <div class="form-errors"><?php echo $error ?></div>
                <?php endif; ?>

                <label for="email">Entrez l'adresse email associé à votre compte:</label>
                <input type="email" id="email" name="email" value="<?php echo $view->escape($email) ?>" />

                <input type="submit" name="login" value="Réinitialiser mon mot de passe" />
            </form>
        </div>
    </div>
<?php $view['slots']->stop() ?>
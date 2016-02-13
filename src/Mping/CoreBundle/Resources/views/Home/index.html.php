<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <div data-role="header">
        <?php if (!$app->getUser()): ?>
            <a href="<?php echo $view['router']->generate('userLogin') ?>" class="ui-btn-left ui-btn ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-user">Connexion</a>
        <?php else: ?>
            <a href="<?php echo $view['router']->generate('userLogout') ?>" class="ui-btn-left ui-btn ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-action" data-ajax="false">Deconnexion</a>
        <?php endif; ?>
        <h1>TTForever</h1>
    </div>

    <div role="main" class="ui-content">
        <div class="ui-grid-a">
            <div class="ui-block-a"><a href="<?php echo $view['router']->generate('profile') ?>" class="ui-btn ui-shadow ui-corner-all">Mon profil</a></div>
            <div class="ui-block-b"><a href="<?php echo $view['router']->generate('profileClub') ?>" class="ui-btn ui-shadow ui-corner-all">Mon club</a></div>
        </div>
        <div class="ui-grid-solo"><div class="ui-block-a">
        <div class="ui-grid-solo"><div class="ui-block-a"><a href="<?php echo $view['router']->generate('favorites') ?>" class="ui-btn ui-shadow ui-corner-all">Favoris</a></div></div>
        <div class="ui-grid-solo"><div class="ui-block-a"><a href="<?php echo $view['router']->generate('players') ?>" class="ui-btn ui-shadow ui-corner-all">Joueurs</a></div></div>
        <div class="ui-grid-solo"><div class="ui-block-a"><a href="<?php echo $view['router']->generate('clubs') ?>" class="ui-btn ui-shadow ui-corner-all">Clubs</a></div></div>
        <div class="ui-grid-solo"><div class="ui-block-a"><a href="<?php echo $view['router']->generate('championships') ?>" class="ui-btn ui-shadow ui-corner-all">Compétitions</a></div></div>
        <div class="ui-grid-solo"><div class="ui-block-a"><a href="<?php echo $view['router']->generate('licences') ?>" class="ui-btn ui-shadow ui-corner-all">Licences</a></div></div>
        <div class="ui-grid-solo"><div class="ui-block-a"><a href="<?php echo $view['router']->generate('rules') ?>" class="ui-btn ui-shadow ui-corner-all">Règlements</a></div></div>
        <div class="ui-grid-solo"><div class="ui-block-a"><a href="<?php echo $view['router']->generate('settings') ?>" class="ui-btn ui-shadow ui-corner-all">Paramètres</a></div></div>
        
    </div>
<?php $view['slots']->stop() ?>
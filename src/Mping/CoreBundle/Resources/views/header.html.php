<div data-role="header" data-add-back-btn="true" data-position="fixed">
    <h1><?php echo isset($title) ? $view->escape($title) : 'TTForever' ?></h1>

    <div data-role="controlgroup" data-type="horizontal" class="ui-mini ui-btn-right">
        <?php if ($view['slots']->has('header_buttons')): ?>
            <?php $view['slots']->output('header_buttons') ?>

            <span class="ui-btn ui-btn-separator">&nbsp;</span>
        <?php endif; ?>

        <a href="<?php echo $view['router']->generate('home') ?>" class="ui-btn ui-corner-all ui-btn-icon-notext ui-icon-home">Accueil</a>
    </div>

    <?php if ($view['slots']->has('header_nav')): ?>
        <?php $view['slots']->output('header_nav') ?>
    <?php endif; ?>
</div>
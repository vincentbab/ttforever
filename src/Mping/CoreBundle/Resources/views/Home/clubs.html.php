<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => 'Rechercher un club')) ?>

    <div role="main" class="">
        <div class="ui-corner-all custom-corners ui-section">
            <div class="ui-bar ui-bar-a">
                <h3>Recherche</h3>
            </div>
            <div class="ui-body ui-body-a">
                <form action="<?php echo $view['router']->generate('clubSearch') ?>" method="GET">
                    <label for="search" class="ui-hidden-accessible">Ville, Code postal, Département, Numéro, ...:</label>
                    <input type="text" name="search" id="search" placeholder="Ville, Code postal, Département, Numéro, ..." />
    
                    <input type="submit" value="Rechercher" />
                </form>
            </div>
        </div>
        
        <div class="ui-corner-all custom-corners ui-section">
            <div class="ui-bar ui-bar-a">
                <h3>Départements</h3>
            </div>
            <div class="ui-body ui-body-a">
                <ul data-role="listview" data-filter="true" data-filter-placeholder="Département..." data-inset="true">
                    <?php if ($favorites): ?>
                        <li data-role="list-divider">Favoris</li>
                        <?php foreach($favorites as $dep): ?>
                            <?php $name = $departements[$dep]; ?>
                            <li data-icon="false">
                                <a href="<?php echo $view['router']->generate('clubSearch', array('departement' => $dep)) ?>" data-transition="slide">
                                    <?php echo $view->escape($dep) ?> - <?php echo $view->escape($name) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li data-role="list-divider">Tous</li>
                    <?php endif; ?>
                    <?php foreach($departements as $dep => $name): ?>
                        <li data-icon="false">
                            <a href="<?php echo $view['router']->generate('clubSearch', array('departement' => $dep)) ?>" data-transition="slide">
                                <?php echo $view->escape($dep) ?> - <?php echo $view->escape($name) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        
        
    </div>
<?php $view['slots']->stop() ?>
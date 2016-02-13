<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->set('nocache', true) ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => 'Favoris')) ?>

    <div data-role="tabs" data-active="0">
        <div data-role="navbar">
            <ul>
                <li><a href="#favoris-joueurs" class="ui-btn-active">Joueurs</a></li>
                <li><a href="#favoris-clubs">Clubs</a></li>
                <!-- <li><a href="#favoris-equipes">Equipes</a></li>  -->
            </ul>
        </div>
        <div id="favoris-joueurs">
            <ul data-role="listview" <?php if (count($favoritePlayers) > 10): ?>data-filter="true" data-filter-placeholder="Rechercher..."<?php endif; ?> data-split-icon="delete" data-split-theme="a">
                <?php foreach($favoritePlayers as $favorite): ?>
                    <li data-icon="false" data-favorite-id="<?php echo $favorite->getId() ?>">
                        <a href="<?php echo $view['router']->generate($favorite->getRoute(), $favorite->getParams()) ?>" data-transition="slide">
                            <?php echo $view->escape($favorite->getName()) ?>
                        </a>
                        
                        <a href="#" class="remove-favorite"
                            data-favorite-id="<?php echo $favorite->getId() ?>"
                            data-url="<?php echo $view['router']->generate('removeFavorite') ?>"
                        >
                            Supprimer
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div id="favoris-clubs">
            <ul data-role="listview" <?php if (count($favoriteClubs) > 10): ?>data-filter="true" data-filter-placeholder="Rechercher..."<?php endif; ?> data-split-icon="delete" data-split-theme="a">
                <?php foreach($favoriteClubs as $favorite): ?>
                    <li data-icon="false" data-favorite-id="<?php echo $favorite->getId() ?>">
                        <a href="<?php echo $view['router']->generate($favorite->getRoute(), $favorite->getParams()) ?>" data-transition="slide">
                            <?php echo $view->escape($favorite->getName()) ?>
                        </a>
                        
                        <a href="#" class="remove-favorite"
                            data-favorite-id="<?php echo $favorite->getId() ?>"
                            data-url="<?php echo $view['router']->generate('removeFavorite') ?>"
                        >
                            Supprimer
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <!-- <div id="favoris-equipes">

        </div> -->
    </div>
<?php $view['slots']->stop() ?>
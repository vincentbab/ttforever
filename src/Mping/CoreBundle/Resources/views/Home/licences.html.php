<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => 'Vérifier une licence')) ?>

    <div data-role="tabs" data-active="0">
        <div data-role="navbar">
            <ul>
                <li><a href="#licence-par-nom" class="ui-btn-active">Par nom</a></li>
                <li><a href="#licence-par-licence">Par licence</a></li>
                <li><a href="#licence-par-club">Par club</a></li>
            </ul>
        </div>
        <div id="licence-par-nom" class="ui-body">
            <form action="<?php echo $view['router']->generate('licenceSearch') ?>" method="GET">
                <label for="search-lastname" class="ui-hidden-accessible">Nom:</label>
                <input type="text" name="lastname" id="search-lastname" placeholder="Nom" />

                <label for="search-firstname" class="ui-hidden-accessible">Prénom:</label>
                <input type="text" name="firstname" id="search-firstname" placeholder="Prénom" />

                <input type="submit" value="Rechercher" />
            </form>
        </div>
        <div id="licence-par-licence" class="ui-body">
            <form action="<?php echo $view['router']->generate('licenceSearch') ?>" method="GET">
                <label for="search-licence" class="ui-hidden-accessible">Numéro de licence:</label>
                <input type="text" name="licence" id="search-licence" placeholder="Numéro de licence" />

                <input type="submit" value="Rechercher" />
            </form>
        </div>
        <div id="licence-par-club" class="ui-body">
            <form action="<?php echo $view['router']->generate('licenceSearch') ?>" method="GET">
                <label for="search-club" class="ui-hidden-accessible">Numéro de club:</label>
                <input type="text" name="club" id="search-club" placeholder="Numéro de club" />

                <input type="submit" value="Rechercher" />
            </form>
        </div>
    </div>
<?php $view['slots']->stop() ?>
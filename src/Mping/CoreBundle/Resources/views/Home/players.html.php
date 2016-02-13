<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => 'Rechercher un joueur')) ?>

    <div role="main" class="">
        <div class="ui-corner-all custom-corners ui-section">
            <div class="ui-bar ui-bar-a">
                <h3>Par nom</h3>
            </div>
            <div class="ui-body ui-body-a">
                <form action="<?php echo $view['router']->generate('playerSearch') ?>" method="GET">
                    <label for="search-lastname" class="ui-hidden-accessible">Nom:</label>
                    <input type="text" name="lastname" id="search-lastname" placeholder="Nom" />
    
                    <label for="search-firstname" class="ui-hidden-accessible">Prénom:</label>
                    <input type="text" name="firstname" id="search-firstname" placeholder="Prénom" />
    
                    <input type="submit" value="Rechercher" />
                </form>
            </div>
        </div>
        
        <div class="ui-corner-all custom-corners ui-section">
            <div class="ui-bar ui-bar-a">
                <h3>Par licence</h3>
            </div>
            <div class="ui-body ui-body-a">
                <form action="<?php echo $view['router']->generate('playerSearch') ?>" method="GET">
                    <label for="search-licence" class="ui-hidden-accessible">Numéro de licence:</label>
                    <input type="text" name="licence" id="search-licence" placeholder="Numéro de licence" />
    
                    <input type="submit" value="Rechercher" />
                </form>
            </div>
        </div>
    </div>
<?php $view['slots']->stop() ?>
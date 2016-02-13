<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo  $view->render('MpingCoreBundle::header.html.php', array('title' => 'Vérifier une licence')) ?>

    <ul data-role="listview" <?php if (count($licences) > 10): ?>data-filter="true" data-filter-placeholder="Recherche..."<?php endif; ?>>
        <?php foreach($licences as $licence): ?>
            <li data-icon="false">
                <a href="<?php echo $view['router']->generate('licence', array('licence' => $licence['licence'])) ?>" data-transition="slide">
                    <h2><?php echo $licence['nom']?> <?php echo $licence['prenom']?></h2>
                    <p>
                        <?php echo $licence['nclub'] ?>
                    </p>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php $view['slots']->stop() ?>
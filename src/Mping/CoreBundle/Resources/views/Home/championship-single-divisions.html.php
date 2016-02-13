<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => 'Divisions')) ?>

    <ul data-role="listview" data-inset="false">
        <?php foreach($divisions as $division): ?>
            <li data-icon="false">
                <a href="<?php echo $view['router']->generate('championshipSingleGroupAll', array('division' => $division['iddivision'])) ?>" data-transition="slide">
                    <?php echo $view->escape($division['libelle']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php $view['slots']->stop() ?>
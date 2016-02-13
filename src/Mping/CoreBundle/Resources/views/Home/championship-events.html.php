<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => 'Championnats')) ?>

    <ul data-role="listview" data-inset="false">
        <li data-role="list-divider">Par équipes</li>
        <?php foreach($teamEvents as $event): ?>
            <li data-icon="false">
                <a href="<?php echo $view['router']->generate('championshipTeamDivisions', array('organisme' => $organisme, 'epreuve' => $event['idepreuve'])) ?>" data-transition="slide">
                    <?php echo $view->escape($event['libelle']) ?>
                </a>
            </li>
        <?php endforeach; ?>

        <li data-role="list-divider">Individuelles</li>
        <?php foreach($singleEvents as $event): ?>
            <li data-icon="false">
                <a href="<?php echo $view['router']->generate('championshipSingleDivisions', array('organisme' => $organisme, 'epreuve' => $event['idepreuve'])) ?>" data-transition="slide">
                    <?php echo $view->escape($event['libelle']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php $view['slots']->stop() ?>
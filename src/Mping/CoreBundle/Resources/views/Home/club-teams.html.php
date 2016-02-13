<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('header_nav') ?>
    <?php echo $view->render('MpingCoreBundle:Home:club-tabs.html.php', array('club' => $club['numero'], 'active' => 'teams')) ?>
<?php $view['slots']->stop() ?>

<?php $view['slots']->start('main') ?>
    <?php echo  $view->render('MpingCoreBundle::header.html.php', array('title' => $club['nom'])) ?>

    <ul data-role="listview" class="ui-small-padding">
        <li data-role="list-divider">Messieurs</li>
        <?php foreach($teamsM as $team): ?>
            <li data-icon="false">
                <a href="<?php echo $view['router']->generate('championshipTeamGroup', array('division' => $team['iddiv'], 'poule' => $team['idpoule'])) ?>">
                    <h4><?php echo $view->escape($team['libequipe'])?></h4>
                    <p><?php echo $view->escape($team['libdivision'])?></p>
                </a>
            </li>
        <?php endforeach; ?>

        <li data-role="list-divider">Dames</li>
        <?php foreach($teamsF as $team): ?>
            <li data-icon="false">
                <a href="<?php echo $view['router']->generate('championshipTeamGroup', array('division' => $team['iddiv'], 'poule' => $team['idpoule'])) ?>">
                    <h4><?php echo $view->escape($team['libequipe'])?></h4>
                    <p><?php echo $view->escape($team['libdivision'])?></p>
                </a>
            </li>
        <?php endforeach; ?>

        <li data-role="list-divider">Autres</li>
        <?php foreach($teamsA as $team): ?>
            <li data-icon="false">
                <a href="<?php echo $view['router']->generate('championshipTeamGroup', array('division' => $team['iddiv'], 'poule' => $team['idpoule'])) ?>">
                    <h4><?php echo $view->escape($team['libequipe'])?></h4>
                    <p><?php echo $view->escape($team['libdivision'])?></p>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php $view['slots']->stop() ?>
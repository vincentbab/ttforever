<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('header_nav') ?>
    <?php echo $view->render('MpingCoreBundle:Home:player-tabs.html.php', array('active' => 'history', 'licence' => $player['licence'])) ?>
<?php $view['slots']->stop() ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => $player['nom'] . ' ' . $player['prenom'])) ?>

    <ul class="ui-small-padding" data-role="listview">
        <?php foreach($history as $h): ?>
            <li data-icon="false">
                <a href="#">
                    <p><strong><?php echo $h['saison'] ?> - Phase <?php echo $h['phase'] ?></strong></p>
                    
                    <p>
                        <?php echo $h['point'] ?>
                        <?php if ($h['echelon'] == 'N'): ?>
                            (<strong>N<?php echo $h['place']?></strong>)
                        <?php endif; ?>
                    </p>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php $view['slots']->stop() ?>
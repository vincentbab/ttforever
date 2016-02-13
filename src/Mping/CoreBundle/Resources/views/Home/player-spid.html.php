<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('header_nav') ?>
    <?php echo $view->render('MpingCoreBundle:Home:player-tabs.html.php', array('active' => 'spid', 'licence' => $player['licence'])) ?>
<?php $view['slots']->stop() ?>

<?php $view['slots']->start('header_buttons') ?>
<a href="#tri-<?php echo $id=uniqid() ?>" data-rel="popup" data-transition="none" class="ui-btn ui-corner-all ui-btn-icon-notext ui-icon-bars">Trier</a>
<div data-role="popup" id="tri-<?php echo $id ?>" data-theme="a">
        <ul data-role="listview" data-inset="true">
            <li data-icon="false"><a href="?tri=date" data-transition="none">Par journée</a></li>
            <li data-icon="false"><a href="?tri=clt" data-transition="none">Par classement</a></li>
            <!-- <li data-icon="false"><a href="?tri=pts" data-transition="none">Par points</a></li> -->
        </ul>
</div>
<?php $view['slots']->stop() ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => $player['nom'] . ' ' . $player['prenom'])) ?>

    <ul data-role="listview" class="match-list">
        <?php $group = null ?>
        <?php foreach($games as $game): ?>
            <?php $currentGroup = $game['group'] ?>
            <?php if ($currentGroup != $group): ?>
                <li data-role="list-divider"><?php echo $currentGroup ?></li>
                <?php $group = $currentGroup ?>
            <?php endif; ?>
            
            <?php if ($game['forfait'] == '0'): ?>
            <li data-icon="false">
                <a href="#">
                    <p class="floatright"><strong><?php echo $view->escape($game['classement']) ?></strong></p>
                    <p><span class="<?php echo $game['victoire'] == 'V' ? 'good' : 'bad'?>"><?php echo $view->escape($game['date']) ?></span></p>
                    <p><strong><?php echo $view->escape($game['nom']) ?></strong></p>
                    <p><?php echo $view->escape($game['epreuve']) ?></p>
                </a>
            </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php $view['slots']->stop() ?>
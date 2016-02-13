<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('header_nav') ?>
    <?php echo $view->render('MpingCoreBundle:Home:player-tabs.html.php', array('active' => 'games', 'licence' => $player['licence'])) ?>
<?php $view['slots']->stop() ?>

<?php $view['slots']->start('header_buttons') ?>
<a href="#tri-<?php echo $id=uniqid() ?>" data-rel="popup" data-transition="none" class="ui-btn ui-corner-all ui-btn-icon-notext ui-icon-bars">Trier</a>
<div data-role="popup" id="tri-<?php echo $id ?>" data-theme="a">
        <ul data-role="listview" data-inset="true">
            <li data-icon="false"><a href="?tri=date" data-transition="none">Par journée</a></li>
            <li data-icon="false"><a href="?tri=clt" data-transition="none">Par classement</a></li>
            <li data-icon="false"><a href="?tri=pts" data-transition="none">Par points</a></li>
        </ul>
</div>
<?php $view['slots']->stop() ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => $player['nom'] . ' ' . $player['prenom'])) ?>

    <div class="ui-content-collapsible">
        <div data-role="collapsible" data-inset="false" data-collapsed="false" data-collapsed-icon="carat-d" data-expanded-icon="carat-u">
            <h3>Non validées (<?php echo count($tempGames) ?>)</h3>
            <ul data-role="listview" class="match-list">
                <?php $group = null ?>
                <?php foreach($tempGames as $game): ?>
                    <?php $currentGroup = $game['group'] ?>
                    <?php if ($currentGroup != $group): ?>
                        <li data-role="list-divider"><?php echo $currentGroup ?></li>
                        <?php $group = $currentGroup ?>
                    <?php endif; ?>

                    <li data-icon="false">
                        <a href="<?php echo $view['router']->generate('playerSearch', array('search' => $game['nom']))?>">
                            <p class="floatright"><strong><?php echo $view->escape($game['classement']) ?></strong></p>
                            <p><span class="<?php echo $game['victoire'] == 'V' ? 'good' : 'bad'?>"><?php echo $view->escape($game['date']) ?></span></p>
                            <p class="floatright"><span class="ui-corner-all <?php echo ($game['pointres'] == 0 ? 'neutral' : ($game['pointres'] > 0 ? 'good' : 'bad'))?>"><?php echo $game['pointres']>0 ? '+' : '' ?><?php echo $view->escape($game['pointres']) ?></span></p>
                            <p><strong><?php echo $view->escape($game['nom']) ?></strong></p>
                            <p>Coef: <?php echo $view->escape($game['coefchamp']) ?></p>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div data-role="collapsible" data-inset="false" data-collapsed="false" data-collapsed-icon="carat-d" data-expanded-icon="carat-u">
            <h3>Validées (<?php echo count($games) ?>)</h3>
            <ul data-role="listview" class="match-list">
                <?php $group = null ?>
                <?php foreach($games as $game): ?>
                    <?php $currentGroup = $game['group'] ?>
                    <?php if ($currentGroup != $group): ?>
                        <li data-role="list-divider"><?php echo $currentGroup ?></li>
                        <?php $group = $currentGroup ?>
                    <?php endif; ?>
                    <li data-icon="false">
                        <a href="<?php echo $view['router']->generate('player', array('licence' => $game['advlic'])) ?>">
                            <p class="floatright"><strong><?php echo $view->escape($game['classement']) ?></strong></p>
                            <p><span class="ui-corner-all <?php echo $game['vd'] == 'V' ? 'good' : 'bad'?>"><?php echo $view->escape($game['date']) ?></span></p>
                            <p class="floatright"><span class="ui-corner-all <?php echo ($game['pointres'] == 0 ? 'neutral' : ($game['pointres'] > 0 ? 'good' : 'bad'))?>"><?php echo $game['pointres']>0 ? '+' : '' ?><?php echo $view->escape($game['pointres']) ?></span></p>
                            <p><strong><?php echo $view->escape($game['advnompre']) ?></strong></p>
                            <!-- <p>Tour: <?php echo empty($game['numjourn']) ? '' : $view->escape($game['numjourn']) ?> - Coef: <?php echo $view->escape($game['coefchamp']) ?> - Epr: <?php echo $view->escape($game['codechamp']) ?></p> -->
                            <p>Coef: <?php echo $view->escape($game['coefchamp']) ?></p>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php $view['slots']->stop() ?>
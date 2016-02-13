<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => "Poule {$groupe['libelle']}")) ?>

    <?php if (count($groups) > 1): ?>
    <div data-role="popup" id="groups-dialog-<?php echo $groupe['iddiv'] ?>-<?php echo $groupe['idgroupe'] ?>" data-overlay-theme="a" data-theme="a" data-dismissible="true">
        <div data-role="header" data-theme="a">
        <h1>Groupes</h1>
        </div>
        <div>
            <div class="ui-grid-<?php echo count($groups)>2 ? 'c' : 'a'?> groups-grid">
                <?php $classes = array('ui-block-a', 'ui-block-b', 'ui-block-c', 'ui-block-d')?>
                <?php $i=0; foreach($groups as $g): ?>
                    <?php if ($g['idgroupe'] === null) continue; ?>
                    <div class="<?php echo $classes[$i % 4]?>">
                        <a class="ui-btn ui-btn-inline <?php echo $g['idgroupe'] == $groupe['idgroupe'] ? 'ui-btn-active ui-state-disabled' : ''?>" href="<?php echo $view['router']->generate('championshipSingleGroup', array('division' => $g['iddiv'], 'groupe' => $g['idgroupe'])) ?>">
                            <?php echo $view->escape($g['libelle'])?>
                        </a>
                    </div>
                <?php $i++; endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="ui-content-collapsible">
        <?php if (count($groups) > 1): ?>
            <a href="#groups-dialog-<?php echo $groupe['iddiv'] ?>-<?php echo $groupe['idgroupe'] ?>" data-rel="popup" data-position-to="window" data-transition="pop" class="ui-btn ui-btn-inline ui-btn-icon-left ui-icon-grid ui-collapsible-button">Groupes</a>
        <?php endif; ?>

        <div data-role="collapsible" data-inset="false" data-collapsed="false" data-collapsed-icon="carat-d" data-expanded-icon="carat-u">
            <h3>Classement</h3>
            <table class="ui-table table-stripe">
                <thead>
                    <tr class="ui-bar-a">
                        <th>Cl.</th>
                        <th>Joueur</th>
                        <th>Pts.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($ranking as $rank): ?>
                    <tr>
                        <td><?php echo $view->escape($rank['rang']  ?: '' ) ?></td>
                        <td>
                            <a href="<?php echo $view['router']->generate('playerSearch', array('search' => $rank['nom']))?>">
                                <?php echo $view->escape($rank['nom']) ?>
                            </a>
                            (<?php echo $view->escape($rank['clt']) ?>)
                        </td>
                        <td><?php echo $view->escape($rank['points'] ?: '') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div data-role="collapsible" data-inset="false" data-collapsed="false" data-collapsed-icon="carat-d" data-expanded-icon="carat-u">
            <h3>Résultats</h3>
            <ul data-role="listview" class="ui-small-padding">
                <?php $lastDivider = '' ?>
                <?php foreach($matchs as $match): ?>
                    <?php if ($match['libelle'] && $match['libelle'] != $lastDivider): ?>
                        <?php $lastDivider = $match['libelle'] ?>
                        <li data-role="list-divider"><?php echo $view->escape($match['libelle']) ?></li>
                    <?php endif; ?>

                    <li data-icon="false">
                        <p>
                            <a href="<?php echo $view['router']->generate('playerSearch', array('search' => $match['vain']))?>"><?php echo $view->escape($match['vain'])?></a>
                             bat
                            <a href="<?php echo $view['router']->generate('playerSearch', array('search' => $match['perd']))?>"><?php echo $view->escape($match['perd'])?></a></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php $view['slots']->stop() ?>
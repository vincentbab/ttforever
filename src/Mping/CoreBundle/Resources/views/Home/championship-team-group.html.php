<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => "Poule {$group['libelle']}")) ?>

    <?php if (count($groups) > 1): ?>
    <div data-role="popup" id="groups-dialog-<?php echo $group['iddiv'] ?>-<?php echo $group['idpoule'] ?>" data-overlay-theme="a" data-theme="a" data-dismissible="true">
        <div data-role="header" data-theme="a">
        <h1>Poules</h1>
        </div>
        <div>
            <div class="ui-grid-<?php echo count($groups)>2 ? (count($groups) == 3 ? 'b' : 'c') : 'a'?> groups-grid">
                <?php $classes = array('ui-block-a', 'ui-block-b', 'ui-block-c', 'ui-block-d')?>
                <?php $i=0; foreach($groups as $g): ?>
                    <div class="<?php echo $classes[$i % 4]?>">
                        <a class="ui-btn ui-btn-inline <?php echo $g['idpoule'] == $group['idpoule'] ? 'ui-btn-active ui-state-disabled' : ''?>" href="<?php echo $view['router']->generate('championshipTeamGroup', array('division' => $g['iddiv'], 'poule' => $g['idpoule'])) ?>">
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
            <a href="#groups-dialog-<?php echo $group['iddiv'] ?>-<?php echo $group['idpoule'] ?>" data-rel="popup" data-position-to="window" data-transition="pop" class="ui-btn ui-btn-inline ui-btn-icon-left ui-icon-grid ui-collapsible-button">Poules</a>
        <?php endif; ?>

        <div data-role="collapsible" data-inset="false" data-collapsed="false" data-collapsed-icon="carat-d" data-expanded-icon="carat-u">
            <h3>Classement</h3>
            <table class="ui-table table-stripe">
                <thead>
                    <tr class="ui-bar-a">
                        <th>Cl.</th>
                        <th>Equipe</th>
                        <th>J.</th>
                        <th>Pt.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($ranking as $rank): ?>
                    <tr>
                        <td><?php echo !empty($rank['clt']) ? $view->escape($rank['clt']) : '-' ?></td>
                        <td>
                            <?php if (!empty($rank['equipe'])): ?>
                                <a href="<?php echo $view['router']->generate('clubSearch', array('search' => $rank['equipe'])) ?>"><?php echo $view->escape($rank['equipe']) ?></a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?php echo !empty($rank['joue']) ? $view->escape($rank['joue']) : '-' ?></td>
                        <td><?php echo !empty($rank['pts']) ? $view->escape($rank['pts']) : '-' ?></td>
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
                    <?php if ($match['libelle'] != $lastDivider): ?>
                        <?php $lastDivider = $match['libelle'] ?>
                        <li data-role="list-divider"><?php echo $view->escape($match['libelle']) ?></li>
                    <?php endif; ?>

                    <li data-icon="false">
                        <a href="<?php echo $view['router']->generate('championshipTeamEncounter', array('link' => base64_encode($match['lien']))) ?>" style="font-weight: normal">
                            <table class="ui-table nopadding">
                                <tr>
                                    <td width="95%"><?php echo $view->escape($match['equa']) ?></td>
                                    <td width="5%"><?php echo !empty($match['scorea']) ? $view->escape($match['scorea']) : '&nbsp;' ?></td>
                                </tr>
                                <tr>
                                    <td width="95%"><?php echo $view->escape($match['equb']) ?></td>
                                    <td width="5%"><?php echo !empty($match['scoreb']) ? $view->escape($match['scoreb']) : '&nbsp;' ?></td>
                                </tr>
                            </table>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php $view['slots']->stop() ?>
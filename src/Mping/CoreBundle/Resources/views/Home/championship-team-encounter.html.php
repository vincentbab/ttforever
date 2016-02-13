<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => "Détail de la rencontre")) ?>

    <table class="encounter-result">
        <tr>
            <td class="encounter-team encounter-team-a">
                <div><?php echo $view->escape($encounter['resultat']['equa'])?></div>
            </td>
            <td class="encounter-score">
                <div class="encounter-score-a"><?php echo empty($encounter['resultat']['resa']) ? '-' : $view->escape($encounter['resultat']['resa'])?></div>
                <div class="encounter-score-b"><?php echo empty($encounter['resultat']['resb']) ? '-' : $view->escape($encounter['resultat']['resb'])?></div>
            </td>
            <td class="encounter-team encounter-team-b">
                <div><?php echo $view->escape($encounter['resultat']['equb'])?></div>
            </td>
        </tr>
    </table>

    <?php if (!empty($encounter['joueur'])): ?>
    <div class="encounter-players">
        <h3 class="ui-bar ui-bar-a">Joueurs</h3>
        <?php //var_dump($encounter['joueur']); exit; ?>
        <table class="ui-table">
            <?php foreach($encounter['joueur'] as $player): ?>
                <tr>
                    <td class="encounter-player encounter-player-a">
                        <div>
                            <a href="<?php echo $view['router']->generate('playerSearch', array('search' => $player['xja']))?>">
                                <?php echo $view->escape($player['xja'])?>
                            </a>
                            <br />
                            <span class="encounter-player-ranking"><?php echo empty($player['xca']) ? '-' : $view->escape($player['xca'])?></span>
                        </div>
                    </td>
                    <td class="encounter-player-space">&nbsp;<br />&nbsp;</td>
                    <td class="encounter-player encounter-player-b">
                        <div>
                            <a href="<?php echo $view['router']->generate('playerSearch', array('search' => $player['xjb']))?>">
                                <?php echo $view->escape($player['xjb'])?>
                            </a>
                            
                            <span class="encounter-player-ranking"><?php echo empty($player['xcb']) ? '-' : $view->escape($player['xcb'])?></span>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>

    <?php if (!empty($encounter['partie'])): ?>
    <div class="encounter-games">
        <h3 class="ui-bar ui-bar-a">Parties</h3>
        <table class="ui-table">
            <?php foreach($encounter['partie'] as $game): ?>
                <tr>
                    <td class="game-player game-player-a">
                        <div>
                            <?php if ($game['ja'] == 'Double'): ?>
                                Double
                            <?php else: ?>
                                <a href="<?php echo $view['router']->generate('playerSearch', array('search' => $game['ja']))?>"><?php echo $view->escape($game['ja'])?>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="game-score">
                        <span class="game-score-a"><?php echo $view->escape($game['scorea'])?></span>
                        <span class="game-score-b"><?php echo $view->escape($game['scoreb'])?></span>
                    </td>
                    <td class="game-player game-player-b">
                        <div>
                            <?php if ($game['ja'] == 'Double'): ?>
                                Double
                            <?php else: ?>
                                <a href="<?php echo $view['router']->generate('playerSearch', array('search' => $game['jb']))?>"><?php echo $view->escape($game['jb'])?>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
<?php $view['slots']->stop() ?>
<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('header_nav') ?>
    <?php echo $view->render('MpingCoreBundle:Home:club-tabs.html.php', array('club' => $club['numero'], 'active' => 'infos')) ?>
<?php $view['slots']->stop() ?>

<?php $view['slots']->start('header_buttons') ?>
    <a href="#" class="add-favorite ui-btn ui-corner-all ui-btn-icon-notext ui-icon-star <?php echo $isFavorite ? 'ui-alt-icon' : '' ?>"
        data-favorite-type="club"
        data-favorite-id="<?php echo $club['numero'] ?>"
        data-favorite-name="<?php echo $club['nom'] ?>"
        data-url="<?php echo $view['router']->generate('addFavorite') ?>"
    >
        Favoris
    </a>
<?php $view['slots']->stop() ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => $club['nom'])) ?>

    <div role="main" class="ui-content">
        <div class="responsive-container responsive-container-<?php echo empty($club['nomcor']) ? '2' : '3' ?>">
            <div class="responsive-block">
                <div class="ui-section ui-corner-all custom-corners">
                    <div class="ui-bar ui-bar-a">
                        <h3>Club</h3>
                    </div>
                    <div class="ui-body ui-body-a">
                        <h4><?php echo $view->escape($club['nom'])?></h4>
                        <p><?php echo $view->escape($club['numero'])?></p>
                        <?php if (!empty($club['web'])): ?>
                        <p><a target="_blank" data-rel="external" href="<?php echo $view->escape($club['web']) ?>" target="_blank"><?php echo $view->escape($club['web'])?></a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div><!--
    
             --><div class="responsive-block">
                <div class="ui-section ui-corner-all custom-corners">
                    <div class="ui-bar ui-bar-a">
                        <h3>Salle</h3>
                    </div>
                    <div class="ui-body ui-body-a">
                        <a target="_blank" data-rel="external" href="<?php echo $mapLink ?>" class="btn-maps ui-mini ui-btn ui-btn-inline ui-btn-icon-left ui-icon-location">Itinéraire</a>
                        <p><?php echo $view->escape($club['nomsalle'])?></p>
                        <?php if (!empty($club['adressesalle1'])): ?>
                        <p><?php echo $view->escape($club['adressesalle1'])?></p>
                        <?php endif; ?>
                        <?php if (!empty($club['adressesalle2']) || !empty($club['adressesalle3'])): ?>
                        <p><?php echo !empty($club['adressesalle2']) ? $view->escape($club['adressesalle2']) : '' ?>, <?php echo !empty($club['adressesalle3']) ? $view->escape($club['adressesalle3']) : ''?></p>
                        <?php endif; ?>
                        <p><?php echo $view->escape($club['codepsalle'])?> - <?php echo $view->escape($club['villesalle'])?></p>
                    </div>
                </div>
            </div><?php if (!empty($club['nomcor'])): ?><!--
            
             --><div class="responsive-block">
                <div class="ui-section ui-corner-all custom-corners">
                    <div class="ui-bar ui-bar-a">
                        <h3>Correspondant</h3>
                    </div>
                    <div class="ui-body ui-body-a">
                        <p><?php echo $view->escape($club['nomcor'])?> <?php echo !empty($club['prenomcor']) ? $view->escape($club['prenomcor']) : ''?></p>
                        <?php if (!empty($club['mailcor'])): ?>
                            <p><a target="_blank" data-rel="external" href="mailto:<?php echo $view->escape($club['mailcor'])?>"><?php echo $view->escape($club['mailcor'])?></a></p>
                        <?php endif; ?>
                        <?php if (!empty($club['telcor'])): ?>
                            <p><a target="_blank" data-rel="external" href="tel:<?php echo $view->escape($club['telcor'])?>"><?php echo $view->escape($club['telcor'])?></a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="ui-graphic">
            <div class="ui-graphic-row">
                <div class="ui-graphic-cell">
                    <ul data-role="listview" data-inset="true" class="ui-small-padding">
                        <li>Joueurs: <span class="ui-li-count"><?php echo $view->escape($club['nbplayer'])?></span></li>
                        <li class="male">Hommes: <span class="ui-li-count"><?php echo $view->escape($club['nbmale']) ?> (<?php echo $view->escape($club['pmale']) ?>%)</span></li>
                        <li class="female">Femmes: <span class="ui-li-count"><?php echo $view->escape($club['nbfemale']) ?> (<?php echo $view->escape($club['pfemale']) ?>%)</span></li>
                    </ul>
                </div>
                <div class="ui-graphic-cell">
                    <canvas id="victoire-canvas" class="graphic-pie" data-pie-data="<?php echo implode(',', array($club['nbmale'], $club['nbfemale'])) ?>" data-pie-color="#3388CC,#E76DA9" width="100" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <div class="responsive-container responsive-container-2">
            <div class="responsive-block">
                <ul data-role="listview" data-inset="true" class="ui-small-padding adv-classement">
                    <li class="li-label">Classements</li>
                    <?php foreach($club['classement'] as $classement => $stats): ?>
                        <li>
                            <span class="hbar-label"><?php echo $classement ?></span>
                            <div class="hbar">
                                <div class="hbar-t" style="width: <?php echo $stats['pt'] ?>%">
                                    <div class="hbar-1 hbar-m" style="width: <?php echo $stats['pm'] ?>%"><span><?php echo $stats['m'] ?></span></div><!--
                                     --><div class="hbar-2 hbar-f" style="width: <?php echo $stats['pf'] ?>%"><span><?php echo $stats['f'] ?></span></div>
                                </div>
                            </div>
                            <span class="ui-li-count"><?php echo $stats['t'] ?></span>
                        </li>
        
                    <?php endforeach; ?>
                </ul>
            </div><!--
             
             --><div class="responsive-block">
                <ul data-role="listview" data-inset="true" class="ui-small-padding adv-classement">
                    <li class="li-label">Categories</li>
                    <?php foreach($club['categorie'] as $cat => $stats): ?>
                        <li>
                            <span class="hbar-label"><?php echo $cat ?></span>
                            <div class="hbar hbar-cat">
                                <div class="hbar-t" style="width: <?php echo $stats['pt'] ?>%">
                                    <div class="hbar-1 hbar-m" style="width: <?php echo $stats['pm'] ?>%"><span><?php echo $stats['m'] ?></span></div><!--
                                     --><div class="hbar-2 hbar-f" style="width: <?php echo $stats['pf'] ?>%"><span><?php echo $stats['f'] ?></span></div>
                                </div>
                            </div>
                            <span class="ui-li-count"><?php echo $stats['t'] ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php $view['slots']->stop() ?>
<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('header_nav') ?>
    <?php echo $view->render('MpingCoreBundle:Home:player-tabs.html.php', array('active' => 'infos', 'licence' => $player['licence'])) ?>
<?php $view['slots']->stop() ?>

<?php $view['slots']->start('header_buttons') ?>
    <a href="#" class="add-favorite ui-btn ui-corner-all ui-btn-icon-notext ui-icon-star <?php echo $isFavorite ? 'ui-alt-icon' : '' ?>"
        data-favorite-type="player"
        data-favorite-id="<?php echo $player['licence'] ?>"
        data-favorite-name="<?php echo $player['nom'] . ' ' . $player['prenom'] ?>"
        data-url="<?php echo $view['router']->generate('addFavorite') ?>"
    >
        Favoris
    </a>
<?php $view['slots']->stop() ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => $player['nom'] . ' ' . $player['prenom'])) ?>

    <div role="main" class="ui-content">
        <div class="photo-container">
            <img src="<?php echo !empty($player['photo']) ? $player['photo'] : $view['assets']->getUrl('bundles/mpingcore/images/nophoto.png') ?>" alt="Photo <?php echo $view->escape($player['nom'])?> <?php echo $view->escape($player['prenom'])?>" onError="this.onerror=null;this.src='<?php echo $view['assets']->getUrl('bundles/mpingcore/images/nophoto.png') ?>';" />
        </div>
        <div class="player-container">
            <h3 class="<?php echo $licence['sexe']=='F' ? 'female' : 'male' ?> text-overflow"><?php echo $view->escape($player['nom'])?> <?php echo $view->escape($player['prenom'])?></h3>
            <p class="text-overflow">N°<?php echo $player['licence'] ?> / <strong><?php echo $licence['raw_type']!='T' ? 'Promo' : $view->escape($player['clast'])?></strong></p>
            <p class="text-overflow">Cat: <strong><?php echo $view->escape($player['categ'])?></strong> - Natio: <strong><?php echo empty($player['natio']) ? '' : $view->escape($player['natio'])?></strong></p>
            <p class="text-overflow">Certif. médical: <strong><?php echo $view->escape($licence['certif'])?></strong></p>
        </div>

        <ul data-role="listview" data-inset="true" class="clearfloat">
            <li><a data-transition="slide" class="ui-btn ui-btn-inline ui-btn-icon-right ui-icon-carat-r" href="<?php echo $view['router']->generate('club', array('numero' => $player['nclub'])) ?>"><?php echo $view->escape($player['club'])?> <span class="nclub">(N°<?php echo $view->escape($player['nclub'])?>)</span></a></li>
        </ul>

        <div class="responsive-container responsive-container-2">
            <div class="responsive-block">
                <ul data-role="listview" data-inset="true" class="ui-small-padding">
                    <li>Points off. en cours <span class="ui-li-count"><?php echo $view->escape($player['valcla'])?></span></li>
                    <li>Points mensuels <span class="ui-li-count"><?php echo $view->escape(number_format($player['point'], 2, '.', ''))?></span></li>
                    <li>Progression mensuelle: <span class="ui-li-count <?php echo $player['progmois']>=0 ? 'good' : 'bad' ?>"><?php echo $player['progmois']>0 ? '+' : '' ?><?php echo $view->escape($player['progmois']) ?></span></li>
                </ul>
            </div><!--
    
             --><div class="responsive-block">
                 <ul data-role="listview" data-inset="true" class="ui-small-padding">
                    <li>Points virtuel: <span class="ui-li-count"><?php echo $view->escape($player['pointvirt'])?></span></li>
                    <li>Progression virtuelle: <span class="ui-li-count <?php echo $player['progvirt']>=0 ? 'good' : 'bad' ?>"><?php echo $player['progvirt']>0 ? '+' : '' ?><?php echo $view->escape($player['progvirt']) ?></span></li>
                </ul>
            </div>
        </div>
        <div class="responsive-container responsive-container-2">
            <div class="responsive-block">
                <ul data-role="listview" data-inset="true" class="ui-small-padding">
                    <li>Points début saison: <span class="ui-li-count"><?php echo $view->escape($player['valinit'])?></span></li>
                    <li>Progression annuelle: <span class="ui-li-count <?php echo $player['progann']>=0 ? 'good' : 'bad' ?>"><?php echo $player['progann']>0 ? '+' : '' ?><?php echo $view->escape($player['progann']) ?></span></li>
                    <li>Progression annuelle virtuelle: <span class="ui-li-count <?php echo $player['progannvirt']>=0 ? 'good' : 'bad' ?>"><?php echo $player['progannvirt']>0 ? '+' : '' ?><?php echo $view->escape($player['progannvirt']) ?></span></li>
                </ul>
            </div><!--
            
             --><div class="responsive-block">
                <ul data-role="listview" data-inset="true" class="ui-small-padding">
                    <li>Rang national <span class="ui-li-count"><?php echo !empty($player['clglob']) ? $view->escape($player['clglob']) : 'n/a'?></span></li>
                    <li>Rang régional <span class="ui-li-count"><?php echo !empty($player['rangreg']) ? $view->escape($player['rangreg']) : 'n/a'?></span></li>
                    <li>Rang départemental <span class="ui-li-count"><?php echo !empty($player['rangdep']) ? $view->escape($player['rangdep']) : 'n/a'?></span></li>
                </ul>
            </div>
        </div>

        <?php if ($player['nbmatch'] == 0): ?>
            <ul data-role="listview" data-inset="true" class="ui-small-padding">
                <li>Parties disputées: <span class="ui-li-count"><?php echo $view->escape($player['nbmatch'])?></span></li>
            </ul>
        <?php else: ?>
        <div class="responsive-container responsive-container-2">
            <div class="responsive-block">
                <div class="ui-graphic">
                    <div class="ui-graphic-row">
                        <div class="ui-graphic-cell">
                            <ul data-role="listview" data-inset="true" class="ui-small-padding">
                                <li>Parties disputées: <span class="ui-li-count"><?php echo $view->escape($player['nbmatch'])?></span></li>
                                <li class="perf">Victoires: <span class="ui-li-count"><?php echo $view->escape($player['nbvictoire']) ?> (<?php echo $view->escape($player['pvictoire']) ?>%)</span></li>
                                <li class="contre">Défaites: <span class="ui-li-count"><?php echo $view->escape($player['nbdefaite']) ?> (<?php echo $view->escape($player['pdefaite']) ?>%)</span></li>
                            </ul>
                        </div>
                        <div class="ui-graphic-cell">
                            <canvas id="victoire-canvas" class="graphic-pie" data-pie-data="<?php echo implode(',', array($player['nbvictoire'], $player['nbdefaite'])) ?>" data-pie-color="#0B0,#F00" width="100" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div><!--

             --><div class="responsive-block">
                <div class="ui-graphic">
                    <div class="ui-graphic-row">
                        <div class="ui-graphic-cell">
                            <ul data-role="listview" data-inset="true" class="ui-small-padding">
                                <li class="vnormal">Vict. normales: <span class="ui-li-count"><?php echo $view->escape($player['nbvnormal']) ?> (<?php echo $view->escape($player['pvnormal']) ?>%)</span></li>
                                <li class="perf">Perfs: <span class="ui-li-count"><?php echo $view->escape($player['nbperf'])?> (<?php echo $view->escape($player['pperf']) ?>%)</span></li>
                                <li class="dnormal">Déf. normales: <span class="ui-li-count"><?php echo $view->escape($player['nbdnormal']) ?> (<?php echo $view->escape($player['pdnormal']) ?>%)</span></li>
                                <li class="contre">Contres: <span class="ui-li-count"><?php echo $view->escape($player['nbcontre']) ?> (<?php echo $view->escape($player['pcontre']) ?>%)</span></li>
                            </ul>
                        </div>
                        <div class="ui-graphic-cell">
                            <canvas id="victoire-canvas" class="graphic-pie" data-pie-data="<?php echo implode(',', array($player['nbvnormal'], $player['nbperf'], $player['nbdnormal'], $player['nbcontre'])) ?>" data-pie-color="#080,#0B0,#A00,#F00" width="100" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <ul data-role="listview" data-inset="true" class="ui-small-padding adv-classement">
            <li class="li-label">Adversaires</li>
            <?php foreach($player['adversaire'] as $classement => $adv): ?>
                <li>
                    <span class="hbar-label"><?php echo $classement ?></span>
                    <div class="hbar">
                        <div class="hbar-t" style="width: <?php echo $adv['pt'] ?>%">
                            <div class="hbar-1 hbar-v" style="width: <?php echo $adv['pv'] ?>%"><span><?php echo $adv['v'] ?></span></div><!--
                             --><div class="hbar-2 hbar-d" style="width: <?php echo $adv['pd'] ?>%"><span><?php echo $adv['d'] ?></span></div>
                        </div>
                    </div>
                    <span class="ui-li-count"><?php echo $adv['t'] ?></span>
                </li>

            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

    </div>
<?php $view['slots']->stop() ?>
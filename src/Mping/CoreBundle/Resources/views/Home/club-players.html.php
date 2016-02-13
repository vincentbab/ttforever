<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('header_nav') ?>
    <?php echo $view->render('MpingCoreBundle:Home:club-tabs.html.php', array('club' => $club['numero'], 'active' => 'players')) ?>
<?php $view['slots']->stop() ?>

<?php $view['slots']->start('header_buttons') ?>
<a href="#tri-<?php echo $id=uniqid() ?>" data-rel="popup" data-transition="none" class="ui-btn ui-corner-all ui-btn-icon-notext ui-icon-bars">Trier</a>
<div data-role="popup" id="tri-<?php echo $id ?>" data-theme="a" data-position-to="origin">
    <ul data-role="listview" data-inset="true" style="min-width: 200px">
        <li data-icon="false" class="ui-li-checkbox">
            <form action="?tri=<?php echo $order ?>">
                <fieldset data-role="controlgroup" data-iconpos="left" >
                    <input type="checkbox" name="promo" value="1" class="licences-promo" id="licences-promo-<?php echo $club['numero']?>" <?php echo $promo ? 'checked' : '' ?>>
                    <label  for="licences-promo-<?php echo $club['numero']?>">Licences promo</label>
                </fieldset>
                <input type="hidden" name="tri" value="<?php echo $order ?>" />
            </form>
        </li>
        <li data-icon="false"><a href="?tri=alpha&promo=<?php echo $promo ?>" data-transition="none">Par nom</a></li>
        <li data-icon="false"><a href="?tri=pts&promo=<?php echo $promo ?>" data-transition="none">Par points</a></li>
        <li data-icon="false"><a href="?tri=clt&promo=<?php echo $promo ?>" data-transition="none">Par classement</a></li>
        <li data-icon="false"><a href="?tri=cat&promo=<?php echo $promo ?>" data-transition="none">Par categorie</a></li>
    </ul>
</div>
<?php $view['slots']->stop() ?>

<?php $view['slots']->start('main') ?>
    <?php echo  $view->render('MpingCoreBundle::header.html.php', array('title' => $club['nom'])) ?>

    <ul data-role="listview" <?php if (count($players) > 10): ?>data-filter="true" data-filter-placeholder="Joueur..."<?php endif; ?>>
        <?php $group = null ?>
        <?php foreach($players as $player): ?>
            <?php $currentGroup = $player['group'] ?>
            <?php if ($currentGroup != $group): ?>
                <li data-role="list-divider"><?php echo $currentGroup ?></li>
                <?php $group = $currentGroup ?>
            <?php endif; ?>
            <li data-icon="false">
                <a href="<?php echo $view['router']->generate('player', array('licence' => $player['licence'])) ?>" data-transition="slide">
                    <?php if ($player['sexe'] == 'M'): ?>
                        <img src="<?php echo $view['assets']->getUrl('bundles/mpingcore/images/male.png') ?>" class="ui-li-icon" />
                    <?php else: ?>
                        <img src="<?php echo $view['assets']->getUrl('bundles/mpingcore/images/female.png') ?>" class="ui-li-icon" />
                    <?php endif; ?>
                    <?php echo $player['nom']?> <?php echo $player['prenom']?>
                    <span class="ui-li-count"><strong>
                        <?php echo $player['display'] ?>
                    </strong></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php $view['slots']->stop() ?>
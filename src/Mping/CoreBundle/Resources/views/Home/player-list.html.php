<ul class="ui-small-padding" data-role="listview" <?php if (count($players) > 10): ?>data-filter="true" data-filter-placeholder="Joueur..."<?php endif; ?>>
    <?php $group = null ?>
    <?php foreach($players as $player): ?>
        <?php if (isset($groupBy) && $groupBy): ?>
            <?php $currentGroup = $player['group'] ?>
            <?php if ($currentGroup != $group): ?>
                <li data-role="list-divider"><?php echo $currentGroup ?></li>
                <?php $group = $currentGroup ?>
            <?php endif; ?>
        <?php endif; ?>
        <li data-icon="false">
            <a href="<?php echo $view['router']->generate('player', array('licence' => $player['licence'])) ?>" data-transition="slide">
                <h2><?php echo $player['nom']?> <?php echo $player['prenom']?></h2>
                <p>
                    <?php echo $player['club'] ?>
                    <span class="floatright"><strong><?php echo $player['clast'] ?></strong></span>
                </p>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
<ul class="ui-small-padding" data-role="listview" data-inset="false" data-filter="true" data-filter-placeholder="Club...">
    <?php foreach($clubs as $club): ?>
        <li data-icon="false">
            <a href="<?php echo $view['router']->generate('club', array('numero' => $club['numero'])) ?>" data-transition="slide">
                <h2><?php echo $view->escape($club['nom'])?></h2>
                <p><?php echo $view->escape($club['numero'])?></p>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
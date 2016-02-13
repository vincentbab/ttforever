<div data-role="navbar">
    <ul>
        <li><a href="<?php echo $active=='infos' ? '#' : $view['router']->generate('player', array('licence' => $licence)) ?>" class="<?php echo $active=='infos' ? 'ui-btn-active' : ''?>" data-transition="none">Fiche</a></li>
        <li><a href="<?php echo $active=='games' ? '#' : $view['router']->generate('playerGames', array('licence' => $licence))?>" class="<?php echo $active=='games' ? 'ui-btn-active' : ''?>" data-transition="none">Parties</a></li>
        <li><a href="<?php echo $active=='history' ? '#' : $view['router']->generate('playerHistory', array('licence' => $licence))?>" class="<?php echo $active=='history' ? 'ui-btn-active' : ''?>" data-transition="none">Historique</a></li>
    </ul>
</div>

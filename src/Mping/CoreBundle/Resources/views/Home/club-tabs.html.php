<div data-role="navbar">
    <ul>
        <li><a href="<?php echo $active=='infos' ? '#' : $view['router']->generate('club', array('numero' => $club)) ?>" class="<?php echo $active=='infos' ? 'ui-btn-active' : ''?>" data-transition="none">Fiche</a></li>
        <li><a href="<?php echo $active=='players' ? '#' : $view['router']->generate('clubPlayers', array('numero' => $club))?>" class="<?php echo $active=='players' ? 'ui-btn-active' : ''?>" data-transition="none">Joueurs</a></li>
        <li><a href="<?php echo $active=='teams' ? '#' : $view['router']->generate('clubTeams', array('numero' => $club))?>" class="<?php echo $active=='teams' ? 'ui-btn-active' : ''?>" data-transition="none">Equipes</a></li>
    </ul>
</div>
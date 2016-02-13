<div data-role="navbar">
    <ul>
        <li><a href="<?php echo $active=='nat' ? '#' : $view['router']->generate('championships') ?>" class="<?php echo $active=='nat' ? 'ui-btn-active' : ''?>" data-transition="none">National</a></li>
        <li><a href="<?php echo $active=='zone' ? '#' : $view['router']->generate('championshipsZone')?>" class="<?php echo $active=='zone' ? 'ui-btn-active' : ''?>" data-transition="none">Zone</a></li>
        <li><a href="<?php echo $active=='reg' ? '#' : $view['router']->generate('championshipsReg')?>" class="<?php echo $active=='reg' ? 'ui-btn-active' : ''?>" data-transition="none">Region</a></li>
        <li><a href="<?php echo $active=='dep' ? '#' : $view['router']->generate('championshipsDep')?>" class="<?php echo $active=='dep' ? 'ui-btn-active' : ''?>" data-transition="none">Departement</a></li>
    </ul>
</div>
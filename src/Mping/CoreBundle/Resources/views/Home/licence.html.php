<?php $view->extend('MpingCoreBundle::layout.html.php') ?>

<?php $view['slots']->start('main') ?>
    <?php echo $view->render('MpingCoreBundle::header.html.php', array('title' => 'Vérifier une licence')) ?>

    <div role="main" class="ui-content licence-content">
        <p>
            <h4><?php echo $view->escape($licence['nom'])?> <?php echo $view->escape($licence['prenom'])?></h4>
            <?php echo $view->escape($licence['licence'])?>
        </p>
        <p>
            <h4><a href="<?php echo $view['router']->generate('club', array('numero' => $licence['numclub'])) ?>"><?php echo $view->escape($licence['nomclub'])?></a></h4>
            <?php echo $view->escape($licence['numclub'])?>
        </p>

        <p>Points: <strong><?php echo $view->escape($licence['point'])?></strong></p>
        <p>Sexe: <strong><?php echo $view->escape($licence['sexe'])?></strong></p>
        <p>Type: <strong><?php echo $view->escape($licence['type'])?></strong></p>
        <p>Catégorie: <strong><?php echo $view->escape($licence['cat'])?></strong></p>
        <p>Validation: <strong><?php echo $view->escape($licence['validation'])?></strong></p>
        <p>Certificat médical: <strong><?php echo $view->escape($licence['certif'])?></strong></p>
    </div>
<?php $view['slots']->stop() ?>
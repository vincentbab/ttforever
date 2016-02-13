<!DOCTYPE html>
<html>
<head>
    <title>TTForever</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <?php foreach ($view['assetic']->stylesheets(array(
        'bundles/mpingcore/css/jquery.mobile.css',
        'bundles/mpingcore/css/main.css',
    ), array('cssrewrite', 'yui_css'), array('output' => 'css/main.css')) as $url): ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $view->escape($url) ?>" />
    <?php endforeach; ?>

    <?php foreach ($view['assetic']->javascripts(array(
        'bundles/mpingcore/js/jquery.js',
        'bundles/mpingcore/js/config.mobile.js',
        'bundles/mpingcore/js/jquery.mobile.js',
        'bundles/mpingcore/js/main.js',
    ) , array('yui_js'), array('output' => 'js/main.js')) as $url): ?>
        <script src="<?php echo $view->escape($url) ?>"></script>
    <?php endforeach; ?>
</head>

<body>
    <div data-role="page" data-theme="a" <?php if($view['slots']->has('nocache')): ?> data-dom-cache="false" <?php endif; ?> >
        <?php if ($view['slots']->has('main')): ?>
            <?php if ($view['session']->hasFlash('notice')): ?>
                <div class="flash-notice">
                <?php foreach ($view['session']->getFlash('notice') as $message): ?>
                        <p><?php echo $view->escape($message) ?></p>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php $view['slots']->output('main') ?>
        <?php endif; ?>
    </div>
</body>
</html>
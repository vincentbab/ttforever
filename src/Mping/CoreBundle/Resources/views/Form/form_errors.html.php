<?php if ($errors): ?>
    <ul class="form-errors">
        <?php foreach ($errors as $error): ?>
            <li><?php echo $error->getMessage() ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif ?>
<?php if (isset($errors[$errorKey])) : ?>
    <div class="errorBox">
            <?php foreach ($errors[$errorKey] as $error) : ?>
                <p class="errorTxt"><?= sanitize($error) ?></p>
            <?php endforeach ?>
    </div>
<?php endif ?>
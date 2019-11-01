<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= sanitize($pageTitle) ?></title>
        <link rel="stylesheet" href="main.css">
    </head>
    <body>  
    <div class="loginWrapper">
        <?php if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) : ?>
            <p><a class="login" href="index.php?log_out"><?= sanitize(trans('Log out')) ?></a></p>
        <?php else : ?>
            <p><a class="login" href="login.php"><?= sanitize(trans('Log in')) ?></a></p>
        <?php endif ?>
    </div>

<?php

require_once '../common.php';

$name = $password = '';
$errors = [];

// validation
if (isset($_POST['submit'])) {

    if (empty($_POST['username'])) {

        $errors['username'][] = trans('Please insert a username');

    } else if ($_POST['username'] !== ADMIN_NAME) {

        $errors['username'][] = trans('Username or password are not correct');

    } else {
        $name = ADMIN_NAME;
    };

    if (empty($_POST['password'])) {

        $errors['password'][] = trans('Please insert a password');

    } else if ($_POST['password'] !== ADMIN_PASS) {

        $errors['password'][] = trans('Username or password are not correct');

    } else {
        $password = ADMIN_PASS;
    };

    if (!$errors) {
        $_SESSION['authenticated'] = true;
        header('Location: products.php');
        die();
    }
};

$pageTitle = trans('Login Shop 1');
include('../header.php');
print_r($errors);
?>

<div class="container">

    <?php if (isset($_GET['unauthorized'])) : ?>
        <span><?= sanitize(trans('Please log in')) ?></span>
    <?php endif ?>

    <form method="POST" align = "left">

        <label for="username"><?= sanitize(trans('Master username: ')) ?></label><br />
        <input type="text" name="username" value="<?= $name; ?>"><br />
        <?php if ($errors['username']) : ?>
            <div class="errorBox">
                <ul>
                    <?php foreach ($errors['username'] as $error) : ?>
                        <li class="errorTxt"><?= sanitize($error) ?></li>
                    <?php endforeach ?>
                </ul>
                    <?php print_r($errors['username']) ?>
            </div>
        <?php endif ?>
        
        <label for="password"><?= sanitize(trans('Master passcode: ')) ?></label><br />
        <input type="password" name="password" value=""><br />

        <input type="submit" name="submit" value="Log in">

    </form>


</div>

<?php include('../footer.php') ?>
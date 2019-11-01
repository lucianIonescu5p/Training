<?php

require_once '../common.php';

$name = $password = '';
$errors = [];

// validation
if (isset($_POST['submit'])) {

    if (empty($_POST['username'])) {

        $errors['username'][] = trans('Please insert a username');

    } else if ($_POST['username'] !== ADMIN_NAME) {

        $errors['username'][] = trans('Incorrect username');

    } else {
        $name = ADMIN_NAME;
    };

    if (empty($_POST['password'])) {

        $errors['password'][] = trans('Incorrect password');

    } else if ($_POST['password'] !== ADMIN_PASS) {

        $errors['password'][] = trans('Wrong');

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

?>

<div class="container">

    <?php if (isset($_GET['unauthorized'])) : ?>
        <span><?= sanitize(trans('Please log in')) ?></span>
    <?php endif ?>

    <form method="POST" align = "left">

        <label for="username"><?= sanitize(trans('Master username: ')) ?></label><br />
        <input type="text" name="username" value="<?= sanitize(trans($name)) ?>"><br />
        <?php $errorKey='username' ?>
        <?php include '../errors.php' ?>
        
        <label for="password"><?= sanitize(trans('Master passcode: ')) ?></label><br />
        <input type="password" name="password" value=""><br />
        <?php $errorKey='password' ?>
        <?php include '../errors.php' ?>

        <input type="submit" name="submit" value="Log in">

    </form>


</div>

<?php include('../footer.php') ?>
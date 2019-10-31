<?php

require_once '../common.php';

$name = $password = '';

$keys = ['username', 'password'];
$errors = [];
array_fill_keys($errors, $keys);

/** validation
 *
 */
if (isset($_POST['submit'])) {

    if (empty($_POST['username'])) {

        $errors['username'] = [];
        array_push($errors['username'], trans('Please insert a username'));

    } else if ($_POST['username'] !== ADMIN_NAME) {

        $errors['username'] = [];
        array_push($errors['username'], trans('Username or password are not correct'));

    } else {
        $name = ADMIN_NAME;
    };

    if (empty($_POST['password'])) {

        $errors['password'] = [];
        array_push($errors['password'], trans('Please insert a password'));

    } else if ($_POST['password'] !== ADMIN_PASS) {

        $errors['password'] = [];
        array_push($errors['password'], trans('Username or password are not correct'));

    } else {
        $password = ADMIN_PASS;
    };

    if (empty($errors)) {
        
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
        <span><?= sanitize_input(trans('Please log in')) ?></span>
    <?php endif ?>

    <form method="POST" align = "left">

        <label for="username"><?= sanitize_input(trans('Master username: ')) ?></label><br />
        <input type="text" name="username" value="<?= $name; ?>"><br />

        <label for="password"><?= sanitize_input(trans('Master passcode: ')) ?></label><br />
        <input type="password" name="password" value=""><br />

        <input type="submit" name="submit" value="Log in">

    </form>

    <?php if (!empty($errors)) : ?>

        <div class="errorBox">
            <ul>
                <?php foreach ($errors as $error => $key) : ?>
                    <?php foreach ($key as $title => $text) : ?>
                        <li class="errorTxt"><?= sanitize_input($text) ?></li>
                    <?php endforeach ?>
                <?php endforeach ?>
            </ul>
        </div>

    <?php endif ?>

</div>

<?php include('../footer.php') ?>
<?php

require_once '../common.php';

$name = $password = '';

$errors = [];

/** validation
 *
 */
if (isset($_POST['submit'])) {

    if (empty($_POST['username'])) {

        array_fill_keys($errors, 'username');
        $errors['username']->array_push([trans('Please enter username')]);

    } else if ($_POST['username'] === ADMIN_NAME) {
        $name = ADMIN_NAME;
    } else {

        array_fill_keys($errors, 'username');
        $errors['username'] = [trans('Username is not correct')];
        
    };

    if (empty($_POST['password'])) {

        array_fill_keys($errors, 'password');
        $errors['password'] = trans('Please enter password');

    } else if ($_POST['password'] === ADMIN_PASS) {
        $password = ADMIN_PASS;
    } else {

        array_fill_keys($errors, trans('password'));
        $errors['password'] = trans('password is not correct');
        
    };

    if (empty($errors)) {
        
        $_SESSION['authenticated'] = true;
        header('Location: products.php');
        die();
        
    }
};
print_r($errors);
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
                <?php foreach ($errors as $error) : ?>
                        <li class="errorTxt"><?= $error ?></li>
                <?php endforeach ?>
            </ul>
        </div>

    <?php endif ?>

</div>

<?php include('../footer.php') ?>
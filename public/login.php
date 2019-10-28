<?php

require_once '../common.php';

$name = $password = '';
$nameErr = $passwordErr = '';

/** validation
 *
 */
if (isset($_POST['submit']))
{
    if($_POST['username'] === ADMIN_NAME){
        $name = ADMIN_NAME;
    } else {
        $nameErr = trans("Master username is required");
    } 
    if($_POST['password'] === ADMIN_PASS){
        $password = ADMIN_PASS;
    } else {
        $passwordErr = trans("Master passcode is required");
    } 

    if(empty($nameErr) && empty($passwordErr))
    {
        
        $_SESSION['authenticated'] = true;
        header('Location: products.php');
        die();
        
    }
};

$pageTitle = trans('Login Shop 1');
include('../header.php');

?>
<div class="container">

    <?php if (!$_SESSION['authenticated']) : ?>
        <span><?= sanitize_input(trans('Please log in')) ?></span>
    <?php endif ?>

    <form method="POST" align = "left">

        <label for="username"><?= sanitize_input(trans('Master username: ')) ?></label><br />
        <input type="text" name="username" value="<?= $name; ?>"><br />
        <span class="error"> <?= $nameErr; ?></span> <br /><br />

        <label for="password"><?= sanitize_input(trans('Master passcode: ')) ?></label><br />
        <input type="password" name="password" value=""><br />
        <span class="error"> <?= $passwordErr; ?></span> <br /><br />

        <input type="submit" name="submit" value="Log in">

    </form>

</div>

<?php include('../footer.php') ?>
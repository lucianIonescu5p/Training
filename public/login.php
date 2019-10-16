<?php

require_once 'common.php';

$name = $password = "";
$nameErr = $passwordErr = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    if($_POST['username'] === ADMINNAME){

        $name = ADMINNAME;

    } elseif (empty($_POST['username'])){

        $nameErr = trans("Master username is required");

    } elseif ($_POST['username'] != ADMINNAME) {

        $nameErr = trans('This is not the master username');

    }

    if($_POST['password'] === ADMINPASS){

        $password = ADMINPASS;

    } elseif (empty($_POST['password'])){
        
        $passwordErr = trans("Master passcode is required");

    } elseif ($_POST['password'] != ADMINPASS) {

        $passwordErr = trans('This is not the master password');

    }
}

if(isset($_POST['submit']))
{

    if($_POST['username'] === ADMINNAME and $_POST['password'] === ADMINPASS){

        $_SESSION['authenticated'] = true;
        header("Location: products.php");

    }

}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= trans('Login Shop 1') ?></title>
        <link rel="stylesheet" href="main.css">
    </head>
    <body>

        <div id="container">

            <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" align = "left">

                <label for="username"><?= trans('Master username: ') ?></label><br />
                <input type="text" name="username" value="<?= $name; ?>"><br />
                    <span class="error"> <?= $nameErr; ?></span> <br /><br />

                <label for="password"><?= trans('Master passcode: ') ?></label><br />
                <input type="password" name="password"><br />
                    <span class="error"> <?= $passwordErr; ?></span> <br /><br />
                    
                <input type="submit" name="submit" value="Log in">

            </form>

        </div>

    </body>
</html>
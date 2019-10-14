<?php

require_once 'common.php';

$name = $password = "";
$nameErr = $passwordErr = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    if($_POST['username'] === ADMINNAME){

        $name = ADMINNAME;

    } else {

        $nameErr = trans('Master username is required');

    }

    if($_POST['password'] === ADMINPASS){

        $password = ADMINPASS;

    } else {

        $passwordErr = trans('Master passcode is required');

    }
}

if(isset($_POST['submit']))
{

    if($_POST['username'] === ADMINNAME && $_POST['password'] === ADMINPASS){

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

        <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="loginForm">

            <label for="username"><?= trans('Master username: ') ?></label>
            <input type="text" name="username"><span class="error"> <?= $nameErr; ?></span> <br /><br />
            <label for="password"><?= trans('Master passcode: ') ?></label>
            <input type="password" name="password"><span class="error"> <?= $passwordErr; ?></span> <br /><br />
            <input type="submit" name="submit" value="Log in">

        </form>

    </div>

</body>
</html>
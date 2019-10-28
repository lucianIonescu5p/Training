<?php

require_once '../common.php';

// add items to the cart
if (isset($_GET['id'])) {
    array_push($_SESSION['cart'], $_GET['id']);
    header("Location: index.php");
    die();
};

$sql = 
    'SELECT * FROM products' . (
    count($_SESSION['cart']) ?
        ' WHERE id 
        NOT IN (' . implode(',', array_fill(0, count($_SESSION['cart']), '?')) . ')' :
        ''
    );

$stmt = $conn->prepare($sql);
$res = $stmt->execute($_SESSION['cart']);
$rows = $stmt->fetchAll();

if (isset($_GET['log_out'])) {

    unset($_SESSION['authenticated']);
    header('Location: index.php');
    die();

}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= sanitize_input(trans('Shop 1')); ?></title>
        <link rel="stylesheet" href="main.css">
    </head>
    <body>

        <div class="loginWrapper">
            <?php if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) : ?>
            <a class="login" href="index.php?log_out"><?= sanitize_input(trans('Log out')) ?></a>
            <?php else : ?>
            <a class="login" href="login.php"><?= sanitize_input(trans('Log in')) ?></a>
            <?php endif ?>

        </div>
        <div class="container">

            <div class="table">
                <table border="1" cellpadding="3">
                    <tr>
                        <th align="middle"><?= sanitize_input(trans('ID')) ?></th>
                        <th align="middle"><?= sanitize_input(trans('Title')) ?></th>
                        <th align="middle"><?= sanitize_input(trans('Description')) ?></th>
                        <th align="middle"><?= sanitize_input(trans('Price')) ?></th>
                        <th align="middle"><?= sanitize_input(trans('Add')) ?></th>
                    </tr>

                    <?php foreach($rows as $row): ?>

                        <tr>
                            <td align="middle"><img src="images/<?= sanitize_input($row['image']) ?>" width="70px" height="70px"></td>
                            <td align="middle"><?= sanitize_input($row['title']) ?></td>
                            <td align="middle"><?= sanitize_input($row['description']) ?></td>
                            <td align="middle"><?= sanitize_input($row['price']) ?></td>
                            <td align="middle"><a href="?id=<?= sanitize_input($row['id']) ?>"><?= trans('Add Item'); ?></a></td>
                        </tr>

                    <?php endforeach; ?>  

                </table>  
            </div>

            <div class="cartWrapper">
                <a class="cartLink" href="cart.php"><?= sanitize_input(trans('Go to cart')); ?></a>
            </div>

        </div>
    </body>
</html>
<?php

require_once 'common.php';


//add items to the cart
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
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();

if (isset($_GET['log_out'])) {

    unset($_SESSION['authenticated']);
    header("Location: index.php");
    die();
 
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= trans('Shop 1'); ?></title>
        <link rel="stylesheet" href="main.css">
    </head>
    <body>

        <div id="loginWrapper">
            <?php if(!(!isset($_SESSION['authenticated']) || !$_SESSION['authenticated'])): ?>
            <a id="login" href="index.php?log_out"><?= trans('Log out') ?></a>
            <?php else: ?>
            <a id="login" href="login.php"><?= trans('Log in') ?></a>
            <?php endif; ?>
        </div>
        <div id="container">

            <div id="table">
                <table border="1" cellpadding="3">
                    <tr>
                        <th align="middle"><?= trans('ID'); ?></th>
                        <th align="middle"><?= trans('Title'); ?></th>
                        <th align="middle"><?= trans('Description'); ?></th>
                        <th align="middle"><?= trans('Price'); ?></th>
                        <th align="middle"><?= trans('Add'); ?></th>
                    </tr>

                    <?php foreach($rows as $row): ?>
                        <tr>
                            <td align="middle"><img src="images/<?= $row['image'] ?>" width="70px" height="70px"></td>
                            <td align="middle"><?= $row['title'] ?></td>
                            <td align="middle"><?= $row['description'] ?></td>
                            <td align="middle"><?= $row['price'] ?></td>
                            <td align="middle"><a href="?id=<?= $row['id']?>"><?= trans('Add Item'); ?></a></td>
                        </tr>
                    <?php endforeach; ?>  

                </table>  
            </div>

            <div id="cartWrapper">
                <a id="cartLink" href="cart.php"><?= trans('Go to cart'); ?></a>
            </div>

        </div>
    </body>
</html>
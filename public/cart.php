<?php

require_once 'common.php';

if(empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
    $empty = "Cart is empty";
} else {
    if(isset($_GET['id'])) {
        array_push($_SESSION['cart']);
        header("Location: cart.php");
    };

    $sql = 
    'SELECT * FROM products 
    WHERE id IN (' . implode(',', array_fill(0, count($_SESSION['cart']), '?')) . ')';

$stmt = $conn->prepare($sql);

$res = $stmt->execute($_SESSION['cart']);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();
};

// if(isset($_GET['id'])) {
//     array_push($_SESSION['cart']);
//     header("Location: cart.php");
// };

// $sql = 
//     'SELECT * FROM products 
//     WHERE id IN (' . implode(',', array_fill(0, count($_SESSION['cart']), '?')) . ')';

// $stmt = $conn->prepare($sql);

// $res = $stmt->execute($_SESSION['cart']);
// $stmt->setFetchMode(PDO::FETCH_ASSOC);
// $rows = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= trans('Cart') ?></title>
    <link rel="stylesheet" href="main.css">
</head>
<body>

    <div id="cartContainer">

        <p><u><?= trans('Cart details:') ?></u></p>

        <table border="1">

            <tr>
                <th><?= trans('Name') ?></th>
                <th><?= trans('Description') ?></th>
                <th><?= trans('Price') ?></th>
                <th><?= trans('Remove from cart') ?></th>
            </tr>

            <?php foreach($rows as $row): ?>

                <tr>
                    <td><?= $row['title'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td><a href="?id=<?= $row['id']?>"><?= trans('Remove') ?></a></td>
                </tr>

            <?php endforeach; ?>

        </table>
        
        <form>

            <input type="text" name="name" value="" placeholder="<?= trans('Name') ?>"> <br />
            <input type="text" name="contactDetails" value="" placeholder="<?= trans('Contact Details') ?>"> <br />
            <textarea rows="4" cols="50" name="comments" value="" placeholder="<?= trans('Comment') ?>"></textarea> <br />
            <input type="submit" name="submit">   

        </form>
    </div>
    <div class="cartLink">
        <a href="index.php" class="cartBtn"><?= trans('Back to index') ?></a>
    </div>
   
</body>
</html>
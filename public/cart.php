<?php

require_once 'common.php';

if(empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array(0);
} 

//remove items from the cart
if (isset($_GET['remove'])) 
{
    foreach ($_SESSION['cart'] as $key => $value) 
    {
        if ($value == $_GET['remove']) {

            unset($_SESSION['cart'][$key]);

        }

        sort($_SESSION['cart']);
        header("Location: cart.php"); 

    }
};

$sql = 
'SELECT * FROM products 
WHERE id IN (' . implode(',', array_fill(0, count($_SESSION['cart']), '?')) . ')';

$stmt = $conn->prepare($sql);
$res = $stmt->execute($_SESSION['cart']);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();

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

        <table border="1" cellpadding="3">

            <tr>
                <th align="middle"><?= trans('Name') ?></th>
                <th align="middle"><?= trans('Description') ?></th>
                <th align="middle"><?= trans('Price') ?></th>
                <th align="middle"><?= trans('Remove from cart') ?></th>
            </tr>

            <?php foreach($rows as $row): ?>

                <tr>
                    <td align="middle"><?= $row['title'] ?></td>
                    <td align="middle"><?= $row['description'] ?></td>
                    <td align="middle"><?= $row['price'] ?></td>
                    <td align="middle"><a href="?remove=<?= $row['id']?>"><?= trans('Remove') ?></a></td>
                </tr>

            <?php endforeach; ?>

        </table>
        
        <form>

            <input type="text" name="name" value="" placeholder="<?= trans('Name') ?>"> <br />
            <textarea rows="2" cols="50" name="contactDetails" placeholder="<?= trans('Contact Details') ?>"></textarea> <br />
            <textarea rows="4" cols="50" name="comments" value="" placeholder="<?= trans('Comment') ?>"></textarea> <br />
            <input type="submit" name="submit">   

        </form>
    </div>
    <div class="cartLink">
        <a href="index.php" class="cartBtn"><?= trans('Back to index') ?></a>
    </div>
   
</body>
</html>
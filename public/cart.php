<?php

session_start(); 

if(!isset($_SESSION['cart']) || $_SESSION['cart']==null)
echo 'No items in the cart';
else
{

    $in=array_unique($_SESSION['cart']);
    require_once 'config.php';
    require_once 'common.php';
    $sql = "SELECT * FROM products WHERE 'id' IN ($in)";
    $result = $conn->query($sql);
    $fullprice=0;
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="main.css">

</head>

<body>

    <div id="cartContainer">
        <p><u>Cart details:</u></p>
        <table border="1">

        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
        </tr>

    <?php for($i=0; $i < $arrlenght; $i++): ?>
    <?php $row=$result; ?>
        <tr>
            <td><?= $row['title'] ?></td>
            <td><?= $row['description'] ?></td>
            <td><?= $row['price'] ?></td>
        </tr>
    <?php endfor; ?>

        </table>

        <form>
            <input type="text" name="name" value="" placeholder="Name"> <br />
            <input type="text" name="contactDetails" value="" placeholder="Contact Details"> <br />
            <textarea rows="4" cols="50" name="comments" value="" placeholder="Comments..."></textarea> <br />
            <input type="submit" name="submit">    
        </form>
    </div>
    <div class="cartLink">
        <a href="index.php" class="cartBtn"> Back to index </a>
    </div>
</body>
</html>
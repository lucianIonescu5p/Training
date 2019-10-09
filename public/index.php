<?php
session_start(); 

if(empty($_SESSION['cart'])) {
    $_SESSION['cart']=array();
};

if(isset($_GET['id'])) {
    array_push($_SESSION['cart'],$_GET['id']);
};

// Create connection
require_once 'config.php';
require_once 'common.php';
    
$inQuery = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
$stmt = $conn->prepare(
    'SELECT * 
     FROM products 
     WHERE id NOT IN (".$inQuery.")'
);

$res = $stmt->execute($_SESSION['cart']);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();
    // $sql = "SELECT * FROM products";
    // $result = $conn->query($sql);
    //  $rows = array();

    //  for($i=0; $i < $result->num_rows; $i++){
    //      $rows[] = $result->fetch_assoc();
    //  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shop 1</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>

<div id="container">

    <div id="table">
        <table border="1">
            <tr>
                <th><?= trans('ID') ?></th>
                <th><?= trans('Title') ?></th>
                <th><?= trans('Description') ?></th>
                <th><?= trans('Price') ?></th>
                <th><?= trans('Add') ?></th>
            </tr>

            <?php foreach($rows as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['title'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td><a href="?id=<?= $row['id']?>">Add Item</a></td>
                </tr>
            <?php endforeach; ?>  

        </table>  
    </div>

    <div class="cartLink">
        <a href="cart.php" class="cartBtn"><?= trans('Go to cart') ?></a>
    </div>

</div>
</body>
</html>
<?php

require_once '../common.php';

$sql = 'SELECT * FROM orders';

$stmt = $conn->prepare($sql);
$res = $stmt->execute();
$rows = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= sanitize_input(trans('Orders')) ?></title>
    <link rel="stylesheet" href="main.css">
</head>
<body>

    <ul>
        <?php foreach($rows as $row): ?>
            <li><?= sanitize_input(trans('Order id: ')) . sanitize_input($row['id']) . sanitize_input(trans('; Name: ')) . sanitize_input($row['name']) . 
                    sanitize_input(trans('; E-mail: ')) . sanitize_input($row['contact_details']) . 
                    sanitize_input(trans('; Price: ')) . sanitize_input($row['price']) . sanitize_input(trans('; Date: ')) . sanitize_input($row['date']) ?> </li> <br />
        <?php endforeach; ?>
    </ul>
    
    <div class="productsBtn">

        <span><a class="cartLink cartBtn" href="products.php"><?= sanitize_input(trans('Back')) ?></a></span>

    </div>

</body>
</html>
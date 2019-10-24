<?php
require_once 'common.php';

$sql = 'SELECT * FROM orders';

$stmt = $conn->prepare($sql);
$res = $stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= trans('Orders') ?></title>
    <link rel="stylesheet" href="main.css">
</head>
<body>

    <ul>
        <?php foreach($rows as $row): ?>
            <li><?= trans('Order id: ') . $row['id'] . trans('; Name: ') . $row['name'] . trans('; E-mail: ') . $row['contact_details'] . trans('; Price: ') . $row['price'] . trans('; Date: ') . $row['date']?> </li>
        <?php endforeach; ?>
    </ul>
    
    <div id="productsBtn">

        <span><a id="cartLink" href="products.php" class="cartBtn"><?= trans('Back') ?></a></span>

    </div>

</body>
</html>
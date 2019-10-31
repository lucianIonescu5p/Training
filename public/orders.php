<?php

require_once '../common.php';
require_once '../auth.php';

$sql = 'SELECT * FROM orders';

$stmt = $conn->prepare($sql);
$res = $stmt->execute();
$rows = $stmt->fetchAll();

$pageTitle = trans('Orders');
include('../header.php');


?>

<?php if (empty($rows)) : ?>
<p><?= sanitize(trans('No orders')) ?></p>
<?php else : ?>
    <ul>
        <?php foreach($rows as $row): ?>

            <li><?= sanitize(trans('Order id: ')) . sanitize($row['id']) . sanitize(trans('; Name: ')) . sanitize($row['name']) .
                        sanitize(trans('; E-mail: ')) . sanitize($row['contact_details']) .
                        sanitize(trans('; Price: ')) . sanitize($row['price']) . sanitize(trans('; Date: ')) . sanitize($row['date']) ?> </li> <br />

        <?php endforeach; ?>
    </ul>

<?php endif ?>

<div class="productsBtn">

    <span><a class="cartLink cartBtn" href="products.php"><?= sanitize(trans('Back')) ?></a></span>

</div>

<?php include('../footer.php') ?>
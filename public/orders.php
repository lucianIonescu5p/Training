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
    
<?php endif ?>

<div class="productsBtn">
    <span><a class="cartLink cartBtn" href="products.php"><?= sanitize(trans('Products')) ?></a></span>
</div>
<?php include('../footer.php') ?>
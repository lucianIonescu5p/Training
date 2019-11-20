<?php

require_once '../common.php';
require_once '../auth.php';

$sql = 'SELECT 
            orders.*,
            order_product.order_id, 
            order_product.product_id,  
            SUM(products.price) AS price
        FROM orders
        JOIN order_product
        ON order_product.order_id = orders.id
        JOIN products
        ON products.id = order_product.product_id
        GROUP BY orders.id';

$stmt = $conn->prepare($sql);
$res = $stmt->execute();
$orders = $stmt->fetchAll();

$pageTitle = trans('Orders');
include('../header.php');
?>

<?php if (empty($orders)) : ?>
    <p><?= sanitize(trans('No orders')) ?></p>
<?php else : ?>
    <table border="1" cellpadding="3">
        <tr>
            <th align="middle"><?= sanitize(trans('ID'))?>
            <th align="middle"><?= sanitize(trans('Name'))?>
            <th align="middle"><?= sanitize(trans('Price'))?>
            <th align="middle"><?= sanitize(trans('Action'))?>
            <th align="middle"><?= sanitize(trans('Created at'))?>
        </tr>

        <?php foreach ($orders as $order) :?>
            <tr>
                <td align="middle"><?= $order['order_id']?></td>
                <td align="middle"><?= $order['name']?></td>
                <td align="middle"><?= $order['price']?></td>
                <td align="middle"><a href = "order.php?id=<?= $order['id']?>" ><?= sanitize(trans('View')) ?></a></td>
                <td align="middle"><?= $order['created_at']?></td>
            </tr>
        <?php endforeach?>
    </table>
<?php endif ?>

<div class="productsBtn">
    <span><a class="cartLink cartBtn" href="products.php"><?= sanitize(trans('Products')) ?></a></span>
</div>
<?php include('../footer.php') ?>
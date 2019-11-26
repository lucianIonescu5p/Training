<?php

require_once '../common.php';
require_once '../auth.php';

$sql = 'SELECT 
            orders.*,
            order_product.order_id, 
            order_product.product_id,  
            products.*
        FROM orders
        JOIN order_product
        ON order_product.order_id = orders.id
        JOIN products
        ON products.id = order_product.product_id
        WHERE orders.id = ?';

$stmt = $conn->prepare($sql);
$res = $stmt->execute([$_GET['id']]);
$order = $stmt->fetchAll();

$pageTitle = trans('Order');
include('../header.php');
?>

    <?php if (empty($order)) : ?>
        <p><?= sanitize(trans('No orders')) ?></p>
    <?php else : ?>

        <p><?= sanitize(trans('Order')) . ' ' . $order[0]['order_id'] ?></p>
        <p><?= sanitize(trans('Name')) . ': ' . $order[0]['name'] ?></p>
        <p><?= sanitize(trans('Email')) . ': ' . $order[0]['email'] ?></p>

        <div class="table">
        <table border="1" cellpadding="3">
            <tr>
                <th align="middle"><?= sanitize(trans('Product')) ?></th>
                <th align="middle"><?= sanitize(trans('Image')) ?></th>
                <th align="middle"><?= sanitize(trans('Title')) ?></th>
                <th align="middle"><?= sanitize(trans('Description')) ?></th>
                <th align="middle"><?= sanitize(trans('Price')) ?></th>
            </tr>

            <?php foreach ($order as $row) : ?>
                <tr>
                    <td align="middle"><?= sanitize($row['product_id']) ?></td>
                    <td align="middle">
                        <?php if ($row['image']) : ?>
                            <img alt="<?= sanitize(trans('Product image')) ?>" src="images/<?= sanitize($row['image']) ?>"
                                width="70px" height="70px">
                        <?php else : ?>
                            <p><?= sanitize(trans('No image')) ?></p>
                        <?php endif ?>
                    </td>
                    <td align="middle"><?= sanitize($row['title']) ?></td>
                    <td align="middle"><?= sanitize($row['description']) ?></td>
                    <td align="middle"><?= sanitize($row['price']) ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>

    <div class="productsBtn">
        <span><a class="cartLink cartBtn" href="orders.php"><?= sanitize(trans('Orders')) ?></a></span>
    </div>
<?php include('../footer.php') ?>
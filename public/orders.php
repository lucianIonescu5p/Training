<?php

require_once '../common.php';

if (!$_SESSION['authenticated']) {

    header('Location: login.php?unauthorized');
    die();

} else {

    $sql = 'SELECT * FROM orders';

    $stmt = $conn->prepare($sql);
    $res = $stmt->execute();
    $rows = $stmt->fetchAll();

    $pageTitle = trans('Orders');
    include('../header.php');
}

?>
<?php if (empty($rows)) : ?>
<p><?= sanitize_input(trans('No orders')) ?></p>
<?php else : ?>
    <ul>
        <?php foreach($rows as $row): ?>

            <li><?= sanitize_input(trans('Order id: ')) . sanitize_input($row['id']) . sanitize_input(trans('; Name: ')) . sanitize_input($row['name']) . 
                        sanitize_input(trans('; E-mail: ')) . sanitize_input($row['contact_details']) . 
                        sanitize_input(trans('; Price: ')) . sanitize_input($row['price']) . sanitize_input(trans('; Date: ')) . sanitize_input($row['date']) ?> </li> <br />

        <?php endforeach; ?>
    </ul>

<?php endif ?>

<div class="productsBtn">

    <span><a class="cartLink cartBtn" href="products.php"><?= sanitize_input(trans('Back')) ?></a></span>

</div>

<?php include('../footer.php') ?>
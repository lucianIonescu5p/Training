<?php

require_once '../common.php';

// add items to the cart
if (isset($_GET['id']) && !in_array($_GET['id'], $_SESSION['cart'])) {
    array_push($_SESSION['cart'], $_GET['id']);
    header("Location: index.php");
    die();
};

$sql = 
    'SELECT * FROM products' . (
    count($_SESSION['cart']) ?
        ' WHERE id 
        NOT IN (' . implode(',', array_fill(0, count($_SESSION['cart']), '?')) . ')' :
        ''
    );

$stmt = $conn->prepare($sql);
$res = $stmt->execute($_SESSION['cart']);
$rows = $stmt->fetchAll();

if (isset($_GET['log_out'])) {
    unset($_SESSION['authenticated']);
    header('Location: index.php');
    die();
}

$pageTitle = trans('Shop 1');
include('../header.php');
?>

<div class="container">
    <div class="table">
        <table border="1" cellpadding="3">
            <tr>
                <th align="middle"><?= sanitize(trans('ID')) ?></th>
                <th align="middle"><?= sanitize(trans('Title')) ?></th>
                <th align="middle"><?= sanitize(trans('Description')) ?></th>
                <th align="middle"><?= sanitize(trans('Price')) ?></th>
                <th align="middle"><?= sanitize(trans('Add')) ?></th>
            </tr>

            <?php foreach ($rows as $row) : ?>
                <tr>
                    <td align="middle">
                        <?php if ($row['image']) : ?>
                            <img alt ="<?= sanitize(trans('Product image')) ?>" src="images/<?= sanitize($row['image']) ?>" width="70px" height="70px">
                        <?php else : ?>
                            <p><?= sanitize(trans('No image')) ?></p>
                        <?php endif ?>
                    </td>
                    <td align="middle"><?= sanitize($row['title']) ?></td>
                    <td align="middle"><?= sanitize($row['description']) ?></td>
                    <td align="middle"><?= sanitize($row['price']) ?></td>
                    <td align="middle"><a href="?id=<?= sanitize($row['id']) ?>"><?= sanitize(trans('Add Item')) ?></a></td>
                </tr>
            <?php endforeach ?>  

        </table>  
    </div>

    <div class="cartWrapper">
        <a class="cartLink" href="cart.php"><?= sanitize(trans('Go to cart')) ?></a>
    </div>
</div>
<?php include('../footer.php') ?>
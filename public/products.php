<?php

require_once '../common.php';
require_once '../auth.php';

$sql = 'SELECT * FROM products';
$stmt = $conn->prepare($sql);
$res = $stmt->execute();
$rows = $stmt->fetchAll();

// Log out
if (isset($_GET['logOut'])) {
    $_SESSION['authenticated'] = 0;

    header('Location: index.php');
    die();
}

// Delete products
if (isset($_GET['delete'])) {
    $sql = 'SELECT * FROM products WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $res = $stmt->execute([$_GET['delete']]);
    $row = $stmt->fetch();

    if ($row['image']) {
        unlink('images/' . $row['image']);
    }

    $deleteSql = 'DELETE FROM products WHERE id = ?';
    $stmt = $conn->prepare($deleteSql);
    $stmt->execute([$_GET['delete']]);

    header('Location: products.php');
    die();
}

$pageTitle = trans('Products');
include('../header.php');
?>

<div>
    <table border="1" cellpadding="3">
            <tr>
                <th align="middle"><?= sanitize(trans('ID')) ?></th>
                <th align="middle"><?= sanitize(trans('Title')) ?></th>
                <th align="middle"><?= sanitize(trans('Description')) ?></th>
                <th align="middle"><?= sanitize(trans('Price')) ?></th>
                <th align="middle"><?= sanitize(trans('Edit')) ?></th>
                <th align="middle"><?= sanitize(trans('Delete')) ?></th>
            </tr>

            <?php foreach ($rows as $row): ?>
                <tr>
                    <td align="middle"><img alt="<?= sanitize(trans('Product image')) ?>" src="images/<?= sanitize($row['image']) ?>" width="70px" height="70px"></td>
                    <td align="middle"><?= sanitize($row['title']) ?></td>
                    <td align="middle"><?= sanitize($row['description']) ?></td>
                    <td align="middle"><?= sanitize($row['price']) ?></td>
                    <td align="middle"><a href="product.php?edit=<?= sanitize($row['id']) ?>"><?= sanitize(trans('Edit')) ?></a></td>
                    <td align="middle"><a href="?delete=<?= sanitize($row['id']) ?>"><?= sanitize(trans('Delete')) ?></a></td>
                </tr>
            <?php endforeach ?>
    </table>
</div>

<div class="productsBtn">
    <span><a class="cartLink" href="product.php" ><?= sanitize(trans('Add product')) ?></a></span>
    <span><a class="cartLink" href="orders.php" ><?= sanitize(trans('Orders')) ?></a></span>
</div>

<?php include('../footer.php') ?>
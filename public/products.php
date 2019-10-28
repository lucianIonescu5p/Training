<?php

require_once '../common.php';

if (!$_SESSION['authenticated']) {

    header('Location: login.php');
    die();

} elseif ($_SESSION['authenticated']) {

    $sql = 'SELECT * FROM products';

    $stmt = $conn->prepare($sql);
    $res = $stmt->execute();
    $rows = $stmt->fetchAll();

}

/** Log out
 *
 */
if (isset($_GET['logOut'])) {

    $_SESSION['authenticated'] = 0;
    header('Location: index.php');
    die();

}

/** Delete product
 *
 */
if (isset($_GET['delete'])) {
    
    $sql = 'SELECT * FROM products WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $res = $stmt->execute(['id' => $_GET['delete']]);
    $rows = $stmt->fetch();

    unlink('images/' . $rows['image']);

    $deleteSql = 'DELETE FROM products WHERE id = :id';
    $stmt = $conn->prepare($deleteSql);
    $stmt->execute(['id' => $_GET['delete']]);

    header('Location: products.php');
    die();
}

/** Edit product
 *
 */
if (isset($_GET['edit'])) {

    $sql = 'SELECT * FROM products WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $res = $stmt->execute(['id' => $_GET['edit']]);
    $rows = $stmt->fetch();

    $_SESSION['edit'] = true;
    $_SESSION['id'] = $rows['id'];

    header('Location: product.php?edit=' . $_SESSION['id']);
    die();
}

$pageTitle = trans('Products');
include('../header.php');

?>
<div>

    <table border="1" cellpadding="3">

            <tr>

                <th align="middle"><?= sanitize_input(trans('ID')); ?></th>
                <th align="middle"><?= sanitize_input(trans('Title')); ?></th>
                <th align="middle"><?= sanitize_input(trans('Description')); ?></th>
                <th align="middle"><?= sanitize_input(trans('Price')); ?></th>
                <th align="middle"><?= sanitize_input(trans('Edit')); ?></th>
                <th align="middle"><?= sanitize_input(trans('Delete')); ?></th>

            </tr>

        <?php foreach($rows as $row): ?>

            <tr>

                <td align="middle"><img src="images/<?= sanitize_input($row['image']) ?>" width="70px" height="70px"></td>
                <td align="middle"><?= sanitize_input($row['title']) ?></td>
                <td align="middle"><?= sanitize_input($row['description']) ?></td>
                <td align="middle"><?= sanitize_input($row['price']) ?></td>
                <td align="middle"><a href="?edit=<?= sanitize_input($row['id']) ?>"><?= sanitize_input(trans('Edit')); ?></a></td>
                <td align="middle"><a href="?delete=<?= sanitize_input($row['id']) ?>"><?= sanitize_input(trans('Delete')); ?></a></td>

            </tr>
                    
        <?php endforeach; ?>  
    </table>
</div>

<div class="productsBtn">

    <span><a class="cartLink" href="?logOut" ><?= sanitize_input(trans('Log out')) ?></a></span>
    <span><a class="cartLink" href="product.php" ><?= sanitize_input(trans('Add product')) ?></a></span>
    <span><a class="cartLink" href="orders.php" ><?= sanitize_input(trans('Orders')) ?></a></span>

</div>

<?php include('../footer.php') ?>
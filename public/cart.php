<?php

require_once '../common.php';

// remove items from the cart
if (isset($_GET['id'])) {

    $key = array_search($_GET['id'], $_SESSION['cart']);  

    if ($key !== false) {
        unset($_SESSION['cart'][$key]); 
    }

    header('Location: cart.php'); 
    die();
}

$sql = 
    'SELECT * FROM products' . (      
    count($_SESSION['cart']) ?
        ' WHERE id 
        IN (' . implode(',', array_fill(0, count($_SESSION['cart']), '?')) . ')' :
        ''
    );

$stmt = $conn->prepare($sql);
$res = $stmt->execute(array_values($_SESSION['cart']));
$rows = $stmt->fetchAll();

$name = $contactDetails = $comments = '';
$totalPrice = 0;
$errors = [];

// validation
if (isset($_POST['checkout'])) {

    $name = $_POST['name'];
    $contactDetails = $_POST['contactDetails'];
    $comments = $_POST['comments'];

    if (empty($_POST['name'])) {
        $errors['name'][] = trans('Name is required');
    }

    if (empty($_POST['contactDetails'])) {
        $errors['email'][] = trans('E-mail is required');
    } elseif (!filter_var($_POST['contactDetails'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'][] = trans('Invalid email format, try someone@example.com');
    }

    if (!$errors) {
        
        // mail
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type:text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: <' . sanitize($contactDetails) . '>' . "\r\n";
        $message = '
            <html>
                <head>
                    <title>' . sanitize(trans('Order')) . '</title>
                </head>
                <body>

                    <p>' . sanitize(trans('Hello, here\'s an order from')) . ' ' . sanitize($name) . '</p>
                    <p>' . sanitize(trans('At: ')) . sanitize(date('d/M/Y H:i:s')) . '</p>
                    <p>' . sanitize(trans('Order details are:')) . '</p>

                    <table border="1" cellpadding="3">

                        <tr>
                            <th align="middle">' . sanitize(trans('Product')) . ' </th>
                            <th align="middle">' . sanitize(trans('Name')) . ' </th>
                            <th align="middle">' . sanitize(trans('Description')) . ' </th>
                            <th align="middle">' . sanitize(trans('Price')) . ' </th>
                        </tr> ';

                        foreach ($rows as $row) {
                            $totalPrice += $row['price'];
                            $message .= ' 
                                <tr>
                                    <td align="middle"><img alt="' .sanitize(trans('Product Image')). '" src="' . URL . '/images/' . $row['image'] . '" width="70px" height="70px"></td>
                                    <td align="middle">' . sanitize($row['title']) . '</td>
                                    <td align="middle">' . sanitize($row['description']) . '</td>
                                    <td align="middle">' . sanitize($row['price']) . '</td>
                                </tr> ';
                        }
                    $message .= ' 
                        <tr>
                            <td colspan="3" align="middle"><b>' . sanitize(trans('Total price')) . '</b></td>
                            <td align="middle"><b>' . sanitize($totalPrice) . '</b></td>
                        </tr>
                    </table>
                    <p> ' . sanitize(trans('Contact details:')) . ' ' . sanitize($contactDetails) . '</p>
                    <p> ' . sanitize(trans('Additional messages:')) . ' ' . sanitize($comments) . '</p>
                </body>
            </html>';

        // log orders
        $sql = 'INSERT INTO orders(name, contact_details, price) VALUES (?, ?, ?)';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $contactDetails, $totalPrice]);
        $last_id = $conn->lastInsertId();

        foreach ($_SESSION['cart'] as $product) {
            $sql = 'INSERT INTO order_product(order_id ,product_id) VALUES (?, ?)';
            $stmt = $conn->prepare($sql);
            $stmt->execute([$last_id, $product]);
        }

        mail(SHOP_MANAGER, trans('New order!'), $message, $headers);
        $_SESSION['cart'] = [];
        header('Location: cart.php?sent=1');
        die();
    } 
}

$pageTitle = trans('Cart');
include('../header.php');
?>

<?php if (empty($_SESSION['cart'])) : ?>

    <?php if (isset($_GET['sent']) && $_GET['sent']) : ?>
        <p><?= sanitize(trans('Your order was sent successfully')) ?></p>
    <?php endif ?>

    <p><?= sanitize(trans('Cart is empty')) ?></p>

<?php else : ?>
    <div>
        <p><u><?= sanitize(trans('Cart details:')) ?></u></p>

        <table border="1" cellpadding="3">

            <tr>
                <th align="middle"><?= sanitize(trans('Product')) ?></th>
                <th align="middle"><?= sanitize(trans('Name')) ?></th>
                <th align="middle"><?= sanitize(trans('Description')) ?></th>
                <th align="middle"><?= sanitize(trans('Price')) ?></th>
                <th align="middle"><?= sanitize(trans('Remove from cart')) ?></th>
            </tr>

            <?php foreach ($rows as $row) : ?>
            <?php $totalPrice += $row['price'] ?>
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
                    <td align="middle"><a href="?id=<?= sanitize($row['id'])?>"><?= trans('Remove') ?></a></td>
                </tr>
                <?php endforeach ?>

            <tr>
                <td colspan="3" align="middle"><b><?= sanitize(trans('Total price')) ?></b></td>
                <td colspan="2" align="middle"><b><?= sanitize($totalPrice) ?></b></td>
            </tr>
        </table>
    </div>

    <form method="POST">

        <input type="text" name="name" value="<?= sanitize($name) ?>" placeholder="<?= sanitize(trans('Name ')) ?>"><br />
        <?php $errorKey='name' ?>
        <?php include '../errors.php' ?>

        <input type="text" name="contactDetails" placeholder="<?= sanitize(trans('Email Address')) ?>" value="<?= sanitize($contactDetails) ?>"><br />
        <?php $errorKey='email' ?>
        <?php include '../errors.php' ?>

        <textarea rows="4" cols="50" name="comments" placeholder="<?= sanitize(trans('Comment')) ?>"><?= sanitize($comments) ?></textarea> <br />

        <input type="submit" name="checkout" value="<?= sanitize(trans('Checkout')) ?>">
    </form>
<?php endif ?>

<div class="cartWrapper">
    <a class="cartLink cartBtn" href="index.php"><?= sanitize(trans('Back to index')) ?></a>
</div>
<?php include('../footer.php') ?>
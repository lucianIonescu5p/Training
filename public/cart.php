<?php

require_once '../common.php';

/** remove items from the cart
 *
 */
if (isset($_GET['id'])) {

    $key = array_search($_GET['id'], $_SESSION['cart']);  

    if ($key !== false) {

        unset($_SESSION['cart'][$key]); 
        header('Location: cart.php'); 
        die();

    } else {

        header('Location: cart.php'); 
        die();

    }

};

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

/** form checkout
 *
 */
$name = $contactDetails = $comments = '';
$totalPrice = 0;

$keys = ['name', 'eMail'];
$errors = [];
array_fill_keys($errors, $keys);

/** validation
 *
 */
if (isset($_POST['checkout'])) {

    if (empty($_POST['name'])) {

        $errors['name'] = [];
        array_push($errors['name'], trans('Name is required'));

    } else {
        $name = $_POST['name'];
    };

    if (empty($_POST['contactDetails'])) {

        $errors['eMail'] = [];
        array_push($errors['eMail'], trans('E-mail is required'));

    } else if (!filter_var($_POST['contactDetails'], FILTER_VALIDATE_EMAIL)) {

        $errors['eMail'] = [];
        array_push($errors['eMail'], trans('Invalid email format, try someone@example.com'));

    } else {
        $contactDetails = $_POST['contactDetails'];
    }

    $comments = $_POST['comments'];
}

/** mail
 *
 */
if (isset($_POST['checkout']) && empty($errors)) {

    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type:text/html; charset=UTF-8' . "\r\n";
    $headers .= 'From: <' . sanitize_input(trans($contactDetails)) . '>' . "\r\n";

    $message = '
        <html>
            <head>
                <title>' . sanitize_input(trans('Order')) . '</title>
            </head>
            <body>

                <p>' . sanitize_input(trans('Hello, here\'s an order from')) . ' ' . sanitize_input($name) . '</p>
                <p>At ' . sanitize_input(date('d/M/Y H:i:s')) . '</p>
                <p>' . sanitize_input(trans('Order details are:')) . '</p>

                <table border="1" cellpadding="3">

                    <tr>
                        <th align="middle">' . sanitize_input(trans('Product')) . ' </th>
                        <th align="middle">' . sanitize_input(trans('Name')) . ' </th>
                        <th align="middle">' . sanitize_input(trans('Description')) . ' </th>
                        <th align="middle">' . sanitize_input(trans('Price')) . ' </th>
        
                    </tr> ';

                    foreach ($rows as $row) {
                        $totalPrice += $row['price'];
                        $message .= ' 
                            <tr>
                                <td align="middle"><img alt="' .sanitize_input(trans('Product Image')). '" src="' . URL . '/images/' . $row['image'] . '" width="70px" height="70px"></td>
                                <td align="middle">' . sanitize_input($row['title']) . '</td>
                                <td align="middle">' . sanitize_input($row['description']) . '</td>
                                <td align="middle">' . sanitize_input($row['price']) . '</td>
                            </tr> ';
                    }
                $message .= ' 
                    <tr>
                        <td colspan=3 align="middle"><b>' . sanitize_input(trans('Total price')) . '</b></td>
                        <td align="middle"><b>' . sanitize_input($totalPrice) . '</b></td>
                    </tr>
                </table>
                <p> ' . sanitize_input(trans('Contact details:')) . ' ' . sanitize_input($contactDetails) . '</p>
                <p> ' . sanitize_input(trans('Additional messages:')) . ' ' . sanitize_input($comments) . '</p>
            </body>
        </html> ';

    mail(SHOP_MANAGER, trans('New order!'), $message, $headers);

    /** log orders
     *
     */
    $sql = 'INSERT INTO orders(name, contact_details, price) VALUES (:name, :contact_details, :price)';

    $stmt = $conn->prepare($sql);
    $stmt->execute(array('name' => $name, 'contact_details' => $contactDetails, 'price' => $totalPrice));
    $last_id = $conn->lastInsertId();

    foreach ($_SESSION['cart'] as $product) {

        $sql = 'INSERT INTO order_product(order_id ,product_id) VALUES (:order_id, :product_id)';

        $stmt = $conn->prepare($sql);
        $stmt->execute(array(':order_id' => $last_id, ':product_id' => $product));

    }

    header('Location: cart.php?mail_sent');
    die();

}

if (isset($_GET['mail_sent'])) {

    $checkoutMessage = trans('Your order was sent succesfully');
    $_SESSION['cart'] = array();

}

$pageTitle = trans('Cart');
include('../header.php');

?>

<div id="cartContainer">

    <?php if (empty($_SESSION['cart'])) : ?>

        <tr>
            <td colspan=5 align="middle"><?= sanitize_input(trans('Cart is empty')); ?></td>
        </tr>

    <?php else : ?>
        <p><u><?= sanitize_input(trans('Cart details:')) ?></u></p>

        <table border="1" cellpadding="3">

            <tr>
                <th align="middle"><?= sanitize_input(trans('Product')) ?></th>
                <th align="middle"><?= sanitize_input(trans('Name')) ?></th>
                <th align="middle"><?= sanitize_input(trans('Description')) ?></th>
                <th align="middle"><?= sanitize_input(trans('Price')) ?></th>
                <th align="middle"><?= sanitize_input(trans('Remove from cart')) ?></th>
            </tr>

            <?php foreach ($rows as $row) : ?>
            <?php $totalPrice += $row['price']; ?>

                <tr>
                    <td align="middle"><img alt="<?= sanitize_input(trans('Product image')) ?> " src="images/<?= sanitize_input($row['image']) ?>" width="70px" height="70px"></td>
                    <td align="middle"><?= sanitize_input($row['title']) ?></td>
                    <td align="middle"><?= sanitize_input($row['description']) ?></td>
                    <td align="middle"><?= sanitize_input($row['price']) ?></td>
                    <td align="middle"><a href="?id=<?= sanitize_input($row['id'])?>"><?= trans('Remove') ?></a></td>
                </tr>

            <?php endforeach ?>

            <tr>
                <td colspan=3 align="middle"><b><?= sanitize_input(trans('Total price')) ?></b></td>
                <td colspan=2 align="middle"><b><?= sanitize_input($totalPrice) ?></b></td>
            </tr>

        </table>
        </div>

            <form method="POST">

                <input type="text" name="name" value="<?= $name; ?>" placeholder="<?= sanitize_input(trans('Name ')) ?>"><br /> 

                <input type="text" name="contactDetails" placeholder="<?= sanitize_input(trans('Email Address')) ?>" value="<?= sanitize_input($contactDetails) ?>"><br /> 

                <textarea rows="4" cols="50" name="comments" placeholder="<?= sanitize_input(trans('Comment')) ?>"><?= sanitize_input($comments) ?></textarea> <br />

                <input type="submit" name="checkout" value="<?= sanitize_input(trans('Checkout')) ?>">

            </form>

            <?php if (!empty($errors)) : ?>

                <div class="errorBox">
                    <ul>
                        <?php foreach ($errors as $error => $key) : ?>
                            <?php foreach ($key as $title => $text) : ?>
                                <li class="errorTxt"><?= sanitize_input($text) ?></li>
                            <?php endforeach ?>
                        <?php endforeach ?>
                    </ul>
                </div>

            <?php endif ?>

    <?php endif ?>

    <?php if (isset($_GET['mail_sent'])) : ?>
        <p class="success"><?= sanitize_input($checkoutMessage) ?></p>  
    <?php endif ?>

<div class="cartWrapper">
    <a class="cartLink cartBtn" href="index.php"><?= sanitize_input(trans('Back to index')) ?></a>
</div>

<?php include('../footer.php') ?>
<?php

require_once 'common.php';

if (empty($_SESSION['cart'])) {

    $_SESSION['cart'] = array();

} 

$empty = trans('Cart is empty');

//remove items from the cart
if (isset($_GET['id'])) 
{

    $key = array_search($_GET['id'], $_SESSION['cart']);  

    if($key !== false){

        unset($_SESSION['cart'][$key]); 
        header("Location: cart.php"); 

    } else {

        header("Location: cart.php"); 

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
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();

//form checkout
$name = $contactDetails = $comments = "";
$nameErr = $contactDetailsErr = "";

//validation
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if (empty($_POST['name'])) {

        $nameErr = trans('Name is required');

    } else {

        $name = sanitize_input($_POST['name']);

    }

    if (empty($_POST['contactDetails'])) {

        $contactDetailsErr = trans('E-mail is required');

    } else {

        $contactDetails = sanitize_input($_POST['contactDetails']);

    }

    $comments = sanitize_input($_POST['comments']);

}

//mail 
if (isset($_POST['checkout']) && !empty($name) && !empty($contactDetails) && !empty($_SESSION['cart']))
{

        $to = SHOPMANAGER;
        $subject = trans("New order!");
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: <" . trans($name) . "@example.com>" . "\r\n";

        $message = "
            <html>
                <head>
                    <title>" . trans('Order') . "</title>
                </head>
                <body>

                    <p>" . trans('Hello, here\'s an order from') . " " . htmlspecialchars($name) . "</p>
                    <p>" . trans('Order details are:') . "</p>

                    <table border='1' cellpadding='3'>

                <tr>
                    <th align=\"middle\">" . trans('Product') . " </th>
                    <th align=\"middle\">" . trans('Name') . " </th>
                    <th align=\"middle\">" . trans('Description') . " </th>
                    <th align=\"middle\">" . trans('Price') . " </th>

                </tr> ";

                foreach ($rows as $row) {
                    $message .= " <tr>
                                    <td align=\"middle\"><img src=\"http://" . $_SERVER['SERVER_NAME'] . "/images/" . $row['image'] . "\" width=\"70px\" height=\"70px\"></td>
                                    <td align=\"middle\">" . $row['title'] . "</td>
                                    <td align=\"middle\">" . $row['description'] . "</td>
                                    <td align=\"middle\">" . $row['price'] . "</td>
                                </tr> ";
                }
                    $message .= " </table>
                    <p> " . trans('Contact details:') . " " . htmlspecialchars($contactDetails) . "</p>
                    <p> " . trans('Additional messages:') . " " . htmlspecialchars($comments) . "</p>
                </body>
            </html> ";

        mail($to, $subject, $message, $headers);
        header("Location: cart.php?mailsent");
        
}

if(isset($_GET['mailsent'])){

    $checkoutMessage = trans("Your order was sent succesfully");
    $_SESSION['cart'] = array();

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= trans('Cart') ?></title>
    <link rel="stylesheet" href="main.css">
</head>
<body>

    <div id="cartContainer">

        <p><u><?= trans('Cart details:') ?></u></p>

        <table border="1" cellpadding="3">

            <tr>
                <th align="middle"><?= trans('Product') ?></th>
                <th align="middle"><?= trans('Name') ?></th>
                <th align="middle"><?= trans('Description') ?></th>
                <th align="middle"><?= trans('Price') ?></th>
                <th align="middle"><?= trans('Remove from cart') ?></th>
            </tr>
            <?php if(empty($_SESSION['cart'])): ?>
            <tr>
                <td colspan=5 align="middle"><?= $empty; ?></td>
            </tr>
            <?php else: ?>
            <?php foreach($rows as $row): ?>

                <tr>
                    <td align="middle"><img src="images/<?= $row['image'] ?>" width="70px" height="70px"></td>
                    <td align="middle"><?= $row['title'] ?></td>
                    <td align="middle"><?= $row['description'] ?></td>
                    <td align="middle"><?= $row['price'] ?></td>
                    <td align="middle"><a href="?id=<?= $row['id']?>"><?= trans('Remove') ?></a></td>
                </tr>

            <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
        <form method="POST">

            <input id="nameInput" type="text" name="name" value="<?= $name; ?>" placeholder="<?= trans('Name ') ?>">
            <span class="error"> *<?= $nameErr; ?></span><br /> <!-- error message -->
            <input type="email" name="contactDetails" placeholder="<?= trans('Email Address') ?>" value="<?= $contactDetails ?>">
            <span class="error"> *<?= $contactDetailsErr; ?></span> <br /> <!-- error message -->
            <textarea rows="4" cols="50" name="comments" value="" placeholder="<?= trans('Comment') ?>"><?= $comments ?></textarea> <br />
            <input type="submit" name="checkout" value="<?= trans('Checkout') ?>">

        </form>

        <?php if(isset($_GET['mailsent'])): ?> 
                <p class="success"><?= trans(':)') ?> <?= $checkoutMessage ?></p>  
        <?php endif; ?>
    

    <div id="cartWrapper">
        <a id="cartLink" href="index.php" class="cartBtn"><?= trans('Back to index') ?></a>
    </div>
   
</body>
</html>
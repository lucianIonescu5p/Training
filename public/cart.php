<?php

require_once 'common.php';

if(empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array(0);
} 

//remove items from the cart
if (isset($_GET['remove'])) 
{
    foreach ($_SESSION['cart'] as $key => $value) 
    {
        if ($value == $_GET['remove']) {

            unset($_SESSION['cart'][$key]);

        }

        sort($_SESSION['cart']);
        header("Location: cart.php"); 

    }
};

$sql = 
'SELECT * FROM products 
WHERE id IN (' . implode(',', array_fill(0, count($_SESSION['cart']), '?')) . ')';

$stmt = $conn->prepare($sql);
$res = $stmt->execute($_SESSION['cart']);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();

//form checkout
$name = $contactDetails = $comments = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
   
    $name = test_input($_POST['name']);
    $contactDetails = test_input($_POST['contactDetails']);
    $comments = test_input($_POST['comments']);

}

//mail 
if(isset($_POST['checkout']))
{

    $to = SHOPMANAGER;
    $subject = "Your order sir!";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <webmaster@example.com>' . "\r\n";

    $message = "
        <html>
            <head>
                <title>Order</title>
            </head>
            <body>

                <p>Hello " . $name . "</p>
                <p>Your order details are:</p>

                <table border='1' cellpadding='3'>

            <tr>
                <th align=\"middle\"> Name </th>
                <th align=\"middle\"> Description </th>
                <th align=\"middle\"> Price </th>

            </tr> ";

             foreach($rows as $row){
                $message .= " <tr>
                                <td align=\"middle\">" . $row['title'] . "</td>
                                <td align=\"middle\">" . $row['description'] . "</td>
                                <td align=\"middle\">" . $row['price'] . "</td>
                            </tr> ";
             }
                $message .= " </table>
                <p> Your Contact details are: " . $contactDetails . "</p>
                <p> Additional messages: " . $comments . "</p>
            </body>
        </html> ";

    mail($to, $subject, $message, $headers);
    header("Location: cart.php?mailsent");

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

            <?php foreach($rows as $row): ?>

                <tr>
                    <td align="middle"><img src="images/<?= $row['id'] ?>.jpg" width="70px" height="70px"></td>
                    <td align="middle"><?= $row['title'] ?></td>
                    <td align="middle"><?= $row['description'] ?></td>
                    <td align="middle"><?= $row['price'] ?></td>
                    <td align="middle"><a href="?remove=<?= $row['id']?>"><?= trans('Remove') ?></a></td>
                </tr>

            <?php endforeach; ?>

        </table>
    </div>
        <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>">

            <input id="nameInput" type="text" name="name" value="<?= $name; ?>" placeholder="<?= trans('Name ') ?>"><br />
            <textarea rows="2" cols="50" name="contactDetails" placeholder="<?= trans('Contact Details') ?>"><?= $contactDetails; ?></textarea> <br />
            <textarea rows="4" cols="50" name="comments" value="" placeholder="<?= trans('Comment') ?>"><?= $comments; ?></textarea> <br />
            <input type="submit" name="checkout" value="<?= trans('Checkout') ?>">   

        </form>

    

    <div class="cartLink">
        <a href="index.php" class="cartBtn"><?= trans('Back to index') ?></a>
    </div>
   
</body>
</html>
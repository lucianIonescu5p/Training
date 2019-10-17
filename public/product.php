<?php

require_once 'common.php';

if ($_SESSION['authenticated'] != 1) {

    echo trans('You need to be a god to enter this page');
    die();

} elseif ($_SESSION['authenticated'] == 1) {

    $title = $description = $image = '';
    $price = null;

    $titleErr = $descriptionErr = $priceErr = '';

    //data validation
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {

        if (empty($_POST['title'])) {

            $titleErr = trans('Please insert a title');

        } else {

            $title = sanitize_input($_POST['title']);

        };

        if (empty($_POST['description'])) {

            $descriptionErr = trans('Please insert a description');

        } else {

            $description = sanitize_input($_POST['description']);

        };

        if (empty($_POST['price'])) {

            $priceErr = trans('Please specify a \'real\' price');

        } elseif ($_POST['price'] < 0) {

            $priceErr = "Please enter a positive integer value.";

        } else {
    
            $price = sanitize_input($_POST['price']);

        }
    }

    //Insert new product
    if (isset($_POST['submit']) && $title != '' && $description != '' && $price != '') {

        $sql = 'INSERT INTO products(title, description, price, image) 
        VALUES (:title, :description, :price, :image)';

        $stmt = $conn->prepare($sql);
        $stmt->execute(array('title' => $title, 'description' => $description, 'price' => $price, 'image' => $image));
        echo 'Inserted';

    }

    //Update product
    if (isset($_POST['update'])) {

        $sql = 'UPDATE products SET title = ' . $title . ', description = ' . $description . ', price = ' . $price . ' WHERE id = ' . $id . '';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

    }

    //Return to products.php
    if (isset($_GET['products'])) {

        $_SESSION['edit'] = false;
        header('Location: products.php');
        die();

    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= trans("Product"); ?></title>
        <link rel="stylesheet" href="main.css">
    </head>
    <body>
        
        <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">

            <input type="text" name="title" value="<?= sanitize_input($title) ?>" placeholder="<?= trans('Insert product title'); ?>">
                <span class="error"> <?= $titleErr; ?></span><br />
            <input type="text" name="description" value="<?= sanitize_input($description) ?>" placeholder="<?= trans('Insert product description'); ?>">
                <span class="error"> <?= $descriptionErr; ?></span><br />
            <input type="number" name="price" value="<?= sanitize_input($price) ?>" placeholder="<?= trans('Insert product price'); ?>">
                <span class="error"> <?= $priceErr; ?></span><br />
            <input type="file" name="image" placeholder="<?= trans('Insert product image'); ?>"><br />

            <?php if($_SESSION['edit']): ?>
                <input type="submit" name="update" value="<?= trans("Update product"); ?>">
            <?php else: ?>
                <input type="submit" name="submit" value="<?= trans("Add product"); ?>">
            <?php endif; ?>
            
        </form>

        <br />

        <a id="cartLink" href="?products" class="cartBtn"><?= trans('Products') ?></a>

    </body>
</html>
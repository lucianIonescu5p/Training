<?php

require_once 'common.php';

if (!$_SESSION['authenticated']) {

    echo trans('You need to be a god to enter this page');
    die();

} elseif ($_SESSION['authenticated'] == 1) {

    $title = $description = $image = '';
    $price = null;

    $titleErr = $descriptionErr = $priceErr = $imageErr = '';

    //data validation
    if (isset($_POST['submit']) || isset($_POST['update']))
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
        
        if (empty($_POST['image'])){
        //image validation
            $file = $_FILES['image'];
            $fileName = $_FILES['image']['name'];
            $fileTmp = $_FILES['image']['tmp_name'];
            $fileSize = $_FILES['image']['size'];
            $fileError = $_FILES['image']['error'];
            $fileType = $_FILES['image']['type'];

            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));

            $allowed = array('jpg', 'jpeg', 'png', 'gif');

            if(in_array($fileActualExt, $allowed)){

                if($fileError === 0){
                    if($fileSize < 150000) {

                        $image = uniqid('', true) . '.' . $fileActualExt;
                        $fileDestination = 'images/' . basename($image);
                        move_uploaded_file($fileTmp, $fileDestination);

                    } else{
                        $imageErr = 'File too big!';
                    }
                } else {
                    $imageErr = 'Sorry, there was an error uploading the image';
                }
            } else {
                $imageErr = 'You cannot upload these types of files. Only jpg/jpeg/pgn/gif allowed.';
            }
        } else {
            $imageErr = 'Please upload an image';
        }
    }

    //insert new product
    if (isset($_POST['submit']) && empty($titleErr) && empty($descriptionErr) && empty($priceErr) && empty($imageErr)) {

        $sql = 'INSERT INTO products(title, description, price, image) 
        VALUES (:title, :description, :price, :image)';

        $stmt = $conn->prepare($sql);
        $stmt->execute(array('title' => $title, 'description' => $description, 'price' => $price, 'image' => $image));
        header('Location: product.php?' . trans('success'));
        die();

    }

    //Update product
    if($_SESSION['edit']){

        $sql = 'SELECT * FROM products WHERE id = ' . $_SESSION['id'];
        $stmt = $conn->prepare($sql);
        $res = $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetch();

        $title = $rows['title'];
        $description = $rows['description'];
        $price = $rows['price'];
    

    }

    if (isset($_POST['update']) && empty($titleErr) && empty($descriptionErr) && empty($priceErr) && empty($imageErr)) {

        $sql = 'UPDATE products SET title = ?, description = ?, price = ?, image = ? WHERE products.id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_POST['title'], $_POST['description'], $_POST['price'], $image, $rows['id']]);

        $_SESSION['edit'] = false;
        
        unlink('images/' . $rows['image']);

        header('Location: product.php?' . trans('success'));
        die();

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
        <title><?= trans('Product'); ?></title>
        <link rel="stylesheet" href="main.css">
    </head>
    <body>
        
        <form method="POST" enctype="multipart/form-data">

            <?php if(isset($_GET['success'])): ?>
            <p class="success"><?= trans('Product updated') ?></p>
            <?php endif; ?>

            <input type="text" name="title" value="<?= sanitize_input($title) ?>" placeholder="<?= trans('Insert product title'); ?>">
            <span class="error"> <?= $titleErr; ?></span><br />
            <input type="text" name="description" value="<?= sanitize_input($description) ?>" placeholder="<?= trans('Insert product description'); ?>">
            <span class="error"> <?= $descriptionErr; ?></span><br />
            <input type="number" name="price" value="<?= sanitize_input($price) ?>" placeholder="<?= trans('Insert product price'); ?>">
            <span class="error"> <?= $priceErr; ?></span><br />
            <input type="file" name="image" placeholder="<?= trans('Insert product image'); ?>" ><img src="<?=$rows['image'] ?>"/>
            <span class="error"> <?= $imageErr; ?></span><br />

            <?php if($_SESSION['edit']): ?>
                <input type="submit" name="update" value="<?= trans('Update product'); ?>">
            <?php else: ?>
                <input type="submit" name="submit" value="<?= trans('Add product'); ?>">
            <?php endif; ?>
            
        </form>

        <br />

        <a id="cartLink" href="?products" class="cartBtn"><?= trans('Products') ?></a>

    </body>
</html>
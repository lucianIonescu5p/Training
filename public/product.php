<?php

require_once '../common.php';

if (!$_SESSION['authenticated']) {

    header('Location: login.php');
    die();

} else {

    $title = $description = $image = '';
    $price = null;

    $titleErr = $descriptionErr = $priceErr = $imageErr = '';

    // data validation
    if (isset($_POST['submit']) || isset($_POST['update'])) {

        if (empty($_POST['title'])) {
            $titleErr = trans('Please insert a title');
        } else {
            $title = $_POST['title'];
        };

        if (empty($_POST['description'])) {
            $descriptionErr = trans('Please insert a description');
        } else {
            $description = $_POST['description'];
        };

        if (empty($_POST['price'])) {
            $priceErr = trans('Please specify a \'real\' price');
        } elseif ($_POST['price'] < 0) {
            $priceErr = trans("Please enter a positive integer value.");
        } else {
            $price = $_POST['price'];
        }
        
        if (empty($_POST['image'])) {

            // image validation
            $file = $_FILES['image'];
            $fileName = $_FILES['image']['name'];
            $fileTmp = $_FILES['image']['tmp_name'];
            $fileSize = $_FILES['image']['size'];
            $fileError = $_FILES['image']['error'];
            $fileType = $_FILES['image']['type'];

            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));

            $allowed = array('jpg', 'jpeg', 'png', 'gif');

            if (in_array($fileActualExt, $allowed)) {

                if ($fileError === 0) {
                    if($fileSize < 150000) {

                        $image = uniqid('', true) . '.' . $fileActualExt;
                        $fileDestination = 'images/' . basename($image);
                        move_uploaded_file($fileTmp, $fileDestination);

                    } else {
                        $imageErr = trans('File too big!');
                    }
                } else {
                    $imageErr = trans('Sorry, there was an error uploading the image');
                }
            } else {
                $imageErr = trans('You cannot upload these types of files. Only jpg/jpeg/pgn/gif allowed.');
            }
        } else {
            $imageErr = trans('Please upload an image');
        }
    
    // insert new product
        if (empty($titleErr) && empty($descriptionErr) && empty($priceErr) && empty($imageErr)) {

            $sql = 'INSERT INTO products(title, description, price, image) VALUES (:title, :description, :price, :image)';

            $stmt = $conn->prepare($sql);
            $stmt->execute(array('title' => $title, 'description' => $description, 'price' => $price, 'image' => $image));
            header('Location: product.php?' . trans('success'));
            die();

        }
    };

    // Update product
    if(isset($_SESSION['edit']) && $_SESSION['edit']){

        $sql = 'SELECT * FROM products WHERE id = ' . $_SESSION['id'];
        $stmt = $conn->prepare($sql);
        $res = $stmt->execute();
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

    // Return to products.php
    if (isset($_GET['products'])) {

        $_SESSION['edit'] = false;
        header('Location: products.php');
        die();

    }
}

$pageTitle = trans('Product');
include('../header.php');

?>
<form method="POST" enctype="multipart/form-data">

    <?php if(isset($_GET['success'])): ?>
    <p class="success"><?= trans('Product updated') ?></p>
    <?php endif; ?>

    <input type="text" name="title" value="<?= sanitize_input($title) ?>" placeholder="<?= trans(sanitize_input('Insert product title')); ?>">
    <span class="error"> <?= $titleErr; ?></span><br />

    <input type="text" name="description" value="<?= sanitize_input($description) ?>" placeholder="<?= trans(sanitize_input('Insert product description')); ?>">
    <span class="error"> <?= $descriptionErr; ?></span><br />

    <input type="number" name="price" value="<?= sanitize_input($price) ?>" placeholder="<?= trans(sanitize_input('Insert product price')); ?>">
    <span class="error"> <?= $priceErr; ?></span><br />

    <input type="file" name="image" placeholder="<?= trans(sanitize_input('Insert product image')); ?>" ><img src="<?=sanitize_input($rows['image']) ?>"/>
    <span class="error"> <?= $imageErr; ?></span><br />

    <?php if ($_SESSION['edit']) : ?>
        <input type="submit" name="update" value="<?= trans(sanitize_input('Update product')); ?>">
    <?php else : ?>
        <input type="submit" name="submit" value="<?= trans(sanitize_input('Add product')); ?>">
    <?php endif ?>
            
</form>

<br />

<a class="cartLink cartBtn" href="?products" ><?= trans(sanitize_input('Products')) ?></a>

<?php include('../footer.php') ?>
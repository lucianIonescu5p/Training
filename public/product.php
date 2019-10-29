<?php

require_once '../common.php';

if (!$_SESSION['authenticated']) {

    header('Location: login.php?unauthorized');
    die();

} else {

    $title = $description = $image = '';
    $price = null;

    $errors = [];

    /** data validation
     *
     */
    if (isset($_POST['submit']) || isset($_POST['update'])) {

        if (empty($_POST['title'])) {
            array_fill_keys($errors, 'title');
            $errors['title'] = [trans('Please insert a title')];
        } else {
            $title = $_POST['title'];
        };

        if (empty($_POST['description'])) {
            array_fill_keys($errors, 'description');
            $errors['description'] = [trans('Please insert a description')];
        } else {
            $description = $_POST['description'];
        };

        if (empty($_POST['price'])) {

            array_fill_keys($errors, 'price');
            $errors['price'] = [trans('Please insert a price')];

        } elseif ($_POST['price'] < 0) {

            array_fill_keys($errors, 'price');
            $errors['price'] = [trans('Please enter a positive integer value.')];

        } else {
            $price = $_POST['price'];
        }
        
        if (!$_FILES['image']) {

            /** image validation
             *
             */
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

                        array_fill_keys($errors, 'image');
                        $errors['image'] = [trans('File too big!')];
                        
                    };
                } else {

                    array_fill_keys($errors, 'image');
                    $errors['image'] = [trans('Sorry, there was an error uploading the image')];

                };
            } else {

                array_fill_keys($errors, 'image');
                $errors['image'] = [trans('You cannot upload these types of files. Only jpg/jpeg/pgn/gif allowed.')];
                
            };
        } else {

            array_fill_keys($errors, 'image');
            $errors['image'] = [trans('Please upload an image')];

        }
print_r($errors);
        /** insert new product
         *
         */
        if (empty($errors)) {

            $sql = 'INSERT INTO products(title, description, price, image) VALUES (:title, :description, :price, :image)';

            $stmt = $conn->prepare($sql);
            $stmt->execute(array('title' => $title, 'description' => $description, 'price' => $price, 'image' => $image));
            header('Location: product.php?' . trans('success'));
            die();

        }
    };

    /** Update product
     *
     */
    if(isset($_SESSION['edit']) && $_SESSION['edit']){

        $sql = 'SELECT * FROM products WHERE id = ?';
        $stmt = $conn->prepare($sql);
        $res = $stmt->execute([$_SESSION['id']]);
        $rows = $stmt->fetch();

        $title = $rows['title'];
        $description = $rows['description'];
        $price = $rows['price'];

    }

    if (isset($_POST['update']) && empty($errors)) {

        $sql = 'UPDATE products SET title = ?, description = ?, price = ?, image = ? WHERE products.id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_POST['title'], $_POST['description'], $_POST['price'], $image, $rows['id']]);

        $_SESSION['edit'] = false;
        
        unlink('images/' . $rows['image']);

        header('Location: product.php?' . trans('success'));
        die();

    }

    /** Return to products.php
     *
     */
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

    <input type="text" name="title" value="<?= sanitize_input($title) ?>" placeholder="<?= trans(sanitize_input('Insert product title')); ?>"> <br />

    <input type="text" name="description" value="<?= sanitize_input($description) ?>" placeholder="<?= trans(sanitize_input('Insert product description')); ?>"> <br />
    

    <input type="number" name="price" value="<?= sanitize_input($price) ?>" placeholder="<?= trans(sanitize_input('Insert product price')); ?>"> <br />
    
    <input type="file" name="image" placeholder="<?= trans(sanitize_input('Insert product image')); ?>" ><br />
    
    <?php if ($_SESSION['edit']) : ?>
        <input type="submit" name="update" value="<?= trans(sanitize_input('Update product')); ?>">
    <?php else : ?>
        <input type="submit" name="submit" value="<?= trans(sanitize_input('Add product')); ?>">
    <?php endif ?>

    <?php if (isset($_SESSION['edit']) && $_SESSION['edit']) : ?>
        <br />
            <img src="images/<?=sanitize_input($rows['image']) ?>"/> 
        <br />
    <?php endif ?>  
</form> 



<?php if (!empty($errors)) : ?>

    <div class="errorBox">
        <ul>
            <?php foreach ($errors as $error) : ?>
                    <li class="errorTxt"><?= sanitize_input($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>

<?php endif ?>

<br />

<a class="cartLink cartBtn" href="?products" ><?= trans(sanitize_input('Products')) ?></a>

<?php include('../footer.php') ?>
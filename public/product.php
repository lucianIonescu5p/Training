<?php

require_once '../common.php';
require_once '../auth.php';

$title = $description = $image = '';
$price = null;
$errors = [];

// data validation
if (isset($_POST['submit'])) {

    if (empty($_POST['title'])) {

        $errors['title'][] = trans('Please insert a title');

    } else {
        $title = $_POST['title'];
    };

    if (empty($_POST['description'])) {

        $errors['description'][] = trans('Please insert a description');

    } else {
        $description = $_POST['description'];
    };

    if (empty($_POST['price'])) {

        $errors['price'][] = trans('Please insert a price');

    } else if ($_POST['price'] < 0) {

        $errors['price'][] = trans('Please enter a positive integer value.');

    } else {
        $price = $_POST['price'];
    }

    if ($_FILES['image']['error'] !== 4) {

        // image validation
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
                    $errors['image'][] = trans('Image is too big!');
                };
            } else {
                $errors['image'][] = trans('Sorry, there was an error uploading the image');
            };
        } else {
            $errors['image'][] = trans('You cannot upload these types of files. Only jpg/jpeg/pgn/gif allowed.');
        };
    } else {
        $errors['image'][] = trans('Please insert an image');
    }

    // insert product
    if (!$errors) {

        $sql = 'INSERT INTO products(title, description, price, image) VALUES (:title, :description, :price, :image)';

        $stmt = $conn->prepare($sql);
        $stmt->execute(array('title' => $title, 'description' => $description, 'price' => $price, 'image' => $image));
        header('Location: product.php?success');
        die();

    }
};

$pageTitle = trans('Product');
include('../header.php');

?>

<form method="POST" action="product.php/<?= $rows['id'] ?>" enctype="multipart/form-data">

    <?php if (isset($_GET['success'])) : ?>
    <p class="success"><?= trans('Product updated') ?></p>
    <?php endif; ?>

    <input type="text" name="title" value="<?= sanitize($title) ?>" placeholder="<?= sanitize(trans('Insert product title')); ?>"> <br />
    <?php $errorKey='title' ?>
    <?php include '../errors.php' ?>

    <input type="text" name="description" value="<?= sanitize($description) ?>" placeholder="<?= sanitize(trans('Insert product description')); ?>"> <br />
    <?php $errorKey='description' ?>
    <?php include '../errors.php' ?>

    <input type="number" name="price" value="<?= sanitize($price) ?>" placeholder="<?= sanitize(trans('Insert product price')); ?>"> <br />
    <?php $errorKey='price' ?>
    <?php include '../errors.php' ?>

    <input type="file" name="image" placeholder="<?= sanitize(trans('Insert product image')); ?>" ><br />
    <?php $errorKey='image' ?>
    <?php include '../errors.php' ?>

    <input type="submit" name="submit" value="<?= sanitize(trans('Submit')) ?>">

</form> 

<br />

<a class="cartLink cartBtn" href="products.php"><?= trans(sanitize('Products')) ?></a>

<?php include('../footer.php') ?>
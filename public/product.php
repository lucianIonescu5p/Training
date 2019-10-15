<?php

require_once 'common.php';
$title = $description = "";
$price = 0;

$titleErr = $descriptionErr = "";
$priceErr = "";

//data validation
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    if (empty($_POST['title'])){

        $titleErr = trans("Please insert a title");

    } else {

        $title = $_POST['title'];

        if (!preg_match("/^[A-Za-z0-9 ]*$/", $title)) {
            $titleErr = trans("Only letters, numbers and white space allowed");
          };

    }

    if(empty($_POST['description'])){

        $descriptionErr = trans("Please insert a description");

    } else {

        $description = $_POST['description'];

        if (!preg_match("/^[A-Za-z0-9 ]*$/",$description)) {
            $descriptionErr = trans("Only letters, numbers and white space allowed");
          };

    }

    if($price == 0){

        $priceErr = trans("Please specify a 'real' price");

    } else {

        $price = $_POST['price'];

    }
}

//Insert new product
if(isset($_POST['submit'])){

    $sql = 'INSERT INTO products(title, description, price) 
    VALUES (:title, :description, :price)';

$stmt = $conn->prepare($sql);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$stmt->execute(array('title' => $title, 'description' => $description, 'price' => $price));
echo 'Inserted';

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
    
    <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>">

        <input type="text" name="title" value="<?= test_input($title) ?>" placeholder="<?= trans('Insert product title'); ?>">
            <span class="error"> <?= $titleErr; ?></span><br />
        <input type="text" name="description" value="<?= test_input($description) ?>" placeholder="<?= trans('Insert product description'); ?>">
            <span class="error"> <?= $descriptionErr; ?></span><br />
        <input type="number" name="price" value="<?= test_input($price) ?>" placeholder="<?= trans('Insert product price'); ?>">
            <span class="error"> <?= $priceErr; ?></span><br />
        <input type="file" name="image" placeholder="<?= trans('Insert product image'); ?>"><br />
        <input type="submit" name="submit" value="<?= trans("Add product"); ?>">

    </form>

    <br />

    <a id="cartLink" href="products.php" class="cartBtn"><?= trans('Products') ?></a>

</body>
</html>
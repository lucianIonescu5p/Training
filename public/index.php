<?php
session_start();
// Create connection
    require_once 'config.php';
    require_once 'common.php';

// Check connection

    if ($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shop 1</title>
</head>
<body>

    <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Add</th>
            </tr>

        <?php for($i=0; $i < $result->num_rows; $i++): ?>
        <?php $row = $result->fetch_assoc(); ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['title'] ?></td>
                <td><?= $row['description'] ?></td>
                <td><?= $row['price'] ?></td>
                <td><a href="#">Add Item</a></td>
            </tr>
        <?php endfor; ?>  

    </table>  

</body>
</html>
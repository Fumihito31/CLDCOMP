<?php
include '../config/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['categoryName'], $_POST['productName'], $_POST['price'])) {
        $categoryName = mysqli_real_escape_string($connection, $_POST['categoryName']);
        $productName = mysqli_real_escape_string($connection, $_POST['productName']);
        $price = mysqli_real_escape_string($connection, $_POST['price']);

        $insertQuery = $connection->prepare("INSERT INTO product_list (category, name, price) VALUES (?, ?, ?)");
        $insertQuery->bind_param("ssd", $categoryName, $productName, $price);

        if ($insertQuery->execute()) {
            header("Location: ../product.php?category=". $categoryName."&status=added");
            exit();
        } else {
            header("Location: ../product.php?category=". $categoryName."&status=error");
        }

        $insertQuery->close();
    } else {
        echo "Error: All form fields are required!";
    }
} else {
    echo "Error: Invalid request method!";
}
?>

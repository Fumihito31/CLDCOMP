<?php
include '../config/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['categoryName'], $_POST['productName'], $_POST['price'])) {
        $categoryName = mysqli_real_escape_string($connection, $_POST['categoryName']);
        $productName = mysqli_real_escape_string($connection, $_POST['productName']);
        $price = mysqli_real_escape_string($connection, $_POST['price']);

        // Default stock and last restock date
        $stock = 0;  // Default stock value
        $lastRestock = date("Y-m-d");

        // Use prepared statement to prevent SQL injection
        $insertQuery = $connection->prepare("INSERT INTO product_list (category, name, price, stock, last_restock) VALUES (?, ?, ?, ?, ?)");
        $insertQuery->bind_param("ssdss", $categoryName, $productName, $price, $stock, $lastRestock);

        if ($insertQuery->execute()) {
            header("Location: ../product.php?category=". urlencode($categoryName) . "&status=added");
            exit();
        } else {
            header("Location: ../product.php?category=". urlencode($categoryName) . "&status=error");
            exit();
        }

        $insertQuery->close();
    } else {
        echo "Error: All form fields are required!";
    }
} else {
    echo "Error: Invalid request method!";
}
?>

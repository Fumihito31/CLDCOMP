<?php
// Include database connection or establish it here
include '../config/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all necessary form fields are set
    if (isset($_POST['categoryName'], $_POST['productName'], $_POST['price'])) {
        // Sanitize input data
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
            exit(); // Ensure script execution stops after redirection
        } else {
            header("Location: ../product.php?category=". urlencode($categoryName) . "&status=error");
            exit(); // Ensure script execution stops after redirection
        }

        // Close prepared statement
        $insertQuery->close();
    } else {
        // Missing form fields
        echo "Error: All form fields are required!";
    }
} else {
    // Invalid request method
    echo "Error: Invalid request method!";
}
?>

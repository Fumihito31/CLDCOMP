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
        
        // You might want to handle stock differently depending on your system's logic

        // Use prepared statement to prevent SQL injection
        $insertQuery = $connection->prepare("INSERT INTO product_list (category, name, price) VALUES (?, ?, ?)");
        $insertQuery->bind_param("ssd", $categoryName, $productName, $price);

        if ($insertQuery->execute()) {
            header("Location: ../product.php?category=". $categoryName."&status=added");
            exit(); // Ensure script execution stops after redirection
        } else {
            header("Location: ../product.php?category=". $categoryName."&status=error");
           
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

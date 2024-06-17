<?php
include '../config/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all necessary form fields are set
    if (isset($_POST['id'], $_POST['name'], $_POST['price'], $_POST['stock'],$_POST['category'])) {
        // Sanitize input data
        $productId = mysqli_real_escape_string($connection, $_POST['id']);
        $productName = mysqli_real_escape_string($connection, $_POST['name']);
        $productPrice = mysqli_real_escape_string($connection, $_POST['price']);
        $productStock = mysqli_real_escape_string($connection, $_POST['stock']);
        $productCategory = mysqli_real_escape_string($connection, $_POST['category']);

        // Use prepared statement to prevent SQL injection
        $updateQuery = $connection->prepare("UPDATE product_list SET name = ?, price = ?, stock = ? WHERE id = ?");
        $updateQuery->bind_param("sdii", $productName, $productPrice, $productStock, $productId);

        if ($updateQuery->execute()) {
            $updateQuery->close(); // Close prepared statement
            header("Location: ../product.php?category=". $productCategory."&status=success");
            exit(); // Ensure script execution stops after redirection
        } else {
            // Error occurred
            header("Location: ../product.php?category=". $productCategory."&status=error");
        }

        // Close prepared statement
        $updateQuery->close();
    } else {
        // Missing form fields
        echo "Error: All form fields are required!";
    }
} else {
    // Invalid request method
    echo "Error: Invalid request method!";
}
?>
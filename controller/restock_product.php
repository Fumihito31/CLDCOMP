<?php
include '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $connection->real_escape_string($_POST['product_id']);
    $restock_amount = $connection->real_escape_string($_POST['restock_amount']);

    // Update product stock and last restock time
    $query = "UPDATE product_list 
              SET stock = stock + $restock_amount, last_restock = NOW() 
              WHERE id = $product_id";
    
    if (mysqli_query($connection, $query)) {
        echo json_encode(['message' => 'Product restocked successfully.']);
    } else {
        echo json_encode(['message' => 'Error restocking product: ' . mysqli_error($connection)]);
    }
}
?>
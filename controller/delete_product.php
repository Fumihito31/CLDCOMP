<?php
include '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = isset($_POST['id']) ? $connection->real_escape_string($_POST['id']) : '';

    if (!empty($productId)) {
        $query = "DELETE FROM product_list WHERE id = '$productId'";
        if (mysqli_query($connection, $query)) {
            echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete product.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid product ID.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
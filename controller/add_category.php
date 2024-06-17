<?php
include '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_name'])) {
    $category_name = mysqli_real_escape_string($connection, $_POST['category_name']);
    $query = "INSERT INTO categories (category_name) VALUES ('$category_name')";

    if (mysqli_query($connection, $query)) {
        header("Location: ../index.php?status=success");
    } else {
        header("Location: ../index.php?status=error");
    }
    exit();
}

// Close the database connection
mysqli_close($connection);
?>

// controller/add_product.php
<?php
include '../config/connection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['categoryName'], $_POST['productName'], $_POST['price'])) {
        $categoryName = mysqli_real_escape_string($connection, $_POST['categoryName']);
        $productName = mysqli_real_escape_string($connection, $_POST['productName']);
        $price = mysqli_real_escape_string($connection, $_POST['price']);

        // Get the category ID
        $categoryQuery = "SELECT id FROM categories WHERE category_name = '$categoryName'";
        $categoryResult = mysqli_query($connection, $categoryQuery);
        if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
            $categoryRow = mysqli_fetch_assoc($categoryResult);
            $categoryId = $categoryRow['id'];

            // Insert product using category ID
            $insertQuery = $connection->prepare("INSERT INTO product_list (category_id, name, price) VALUES (?, ?, ?)");
            $insertQuery->bind_param("isd", $categoryId, $productName, $price);

            if ($insertQuery->execute()) {
                header("Location: ../product.php?category=". $categoryName."&status=added");
                exit();
            } else {
                header("Location: ../product.php?category=". $categoryName."&status=error");
            }

            $insertQuery->close();
        } else {
            echo "Error: Category not found!";
        }
    } else {
        echo "Error: All form fields are required!";
    }
} else {
    echo "Error: Invalid request method!";
}
?>

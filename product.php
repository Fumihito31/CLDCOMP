<?php include 'config/connection.php';


// Get the selected category from the query parameter
if (isset($_GET['category'])) {
  $category = $connection->real_escape_string($_GET['category']);
} else {
  // Redirect to index.php if 'category' is not set
  header("Location: index.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grocery Product Inventory</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.css">

    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <?php include "navbar.php"?>
  

    <!-- Sidebar -->
    <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white">
        <div class="position-sticky">
            <div class="list-group list-group-flush mx-3 mt-4">
                <?php
                // Fetch categories from the database
                $query = "SELECT * FROM categories";
                $result = mysqli_query($connection, $query);

                // Check if any categories were found
                if (mysqli_num_rows($result) > 0) {
                    // Output data of each row
                      // Output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<a href="product.php?category=' . urlencode($row["category_name"]) . '" class="list-group-item list-group-item-action py-2 ripple">';
                    echo '<i class="fas fa-tachometer-alt fa-fw me-3"></i><span>' . htmlspecialchars($row["category_name"]) . '</span>';
                    echo '</a>';
                }
                } else {
                    echo '<p>No categories found.</p>';
                }
                ?>
            </div>
            
        </div>
    </nav>
    <!-- Sidebar -->




   <!-- Add Category Modal -->
   <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addCategoryModalLabel">Add New Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="addCategoryForm" action="controller/add_product.php" method="POST">
              <div class="mb-3">
                <label for="categoryName" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="categoryName" name="categoryName" required>
                <label for="productName" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="productName" name="productName" required>
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control" id="price" name="price" required>

               

              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" form="addCategoryForm" class="btn btn-primary">Save Product</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End Add Category Modal -->



    <!-- Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="statusModalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- End of Status Modal -->

    <!-- Update Product Modal -->
    <div class="modal fade" id="updateProductModal" tabindex="-1" aria-labelledby="updateProductModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="updateProductModalLabel">Update Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="updateProductForm" action="controller/update_product.php" method="POST" >
              <div class="mb-3">

              <input type="hidden" name="id" id="updateProductId" value="">

              <label for="updateProductName" class="form-label">Product Name</label>
              <input type="text" class="form-control" id="updateProductName" name="name" required>

            
              <input type="hidden" class="form-control" id="updateProductCategory" name="category" required>

              <label for="updatePrice" class="form-label">Price</label>
              <input type="text" class="form-control" id="updatePrice" name="price" required>

              <label for="updateProductStock" class="form-label">Stock</label>
              <input type="number" class="form-control" id="updateProductStock" name="stock" required>
                     
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" form="updateProductForm" class="btn btn-primary">Update Product</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End Update Product Modal -->

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteConfirmationModalLabel">Delete Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this product?</p>
        <input type="hidden" id="deleteProductId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>
    <!-- End Delete Confirmation Modal -->

    <!-- Restock Product Modal -->
    <div class="modal fade" id="restockProductModal" tabindex="-1" aria-labelledby="restockProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="restockProductModalLabel">Restock Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="restockProductForm">
          <div class="mb-3">
            <label for="restockAmount" class="form-label">Amount to Restock</label>
            <input type="number" class="form-control" id="restockAmount" name="restock_amount" required>
            <input type="hidden" id="restockProductId" name="product_id">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" form="restockProductForm" class="btn btn-primary">Restock</button>
      </div>
    </div>
  </div>
</div>
    <!-- End Restock Product Modal -->

    <main class="mt-5 pt-3">
      
        <div class="container-fluid">
        <h2 class="py-3"><?php echo $category?></h2>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <span><i class="bi bi-table me-2"></i></span> Product list
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table table-striped data-table" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Stock</th>
                                            <th>Price</th>
                                            <th>Last Restock</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                            $query = "SELECT * FROM product_list where category='$category'";
                            $result = mysqli_query($connection, $query);
                            
                            // Check if any categories were found
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                  echo '<tr>';
                                  echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                                  echo '<td>' . htmlspecialchars($row['stock']) . '</td>';
                                  echo '<td>₱' . htmlspecialchars($row['price']) . '</td>';
                                  echo '<td>' . htmlspecialchars($row['last_restock']) . '</td>';
                                  echo '<td>
                                          <button class="btn btn-warning btn-sm update-btn" 
                                                  data-bs-toggle="modal" 
                                                  data-bs-target="#updateProductModal" 
                                                  data-product-id="' . $row['id'] . '"
                                                  data-product-name="' . htmlspecialchars($row['name']) . '"
                                                  data-product-category="' . htmlspecialchars($row['category']) . '"
                                                  data-product-price="' . htmlspecialchars($row['price']) . '"
                                                  data-product-stock="' . htmlspecialchars($row['stock']) . '">Update</button>
                                          <button class="btn btn-danger btn-sm delete-btn" 
                                                  data-bs-toggle="modal" 
                                                  data-bs-target="#deleteConfirmationModal"
                                                  data-product-id="' . $row['id'] . '">Delete</button>
                                          <button class="btn btn-success btn-sm restock-btn" 
                                                  data-bs-toggle="modal" 
                                                  data-bs-target="#restockProductModal"
                                                  data-product-id="' . $row['id'] . '">Restock</button>
                                        </td>';
                                  echo '</tr>';
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No products found</td></tr>";
                            }
                            ?>
                                    </tbody>
                                </table>
                            </div>
                
            </div>
 </div>          
        </div>
    </div>
   
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Product</button>
           
</div>


    </main>
    

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.js"></script>

    <script>
      $(document).ready(function() {
    $('#restockProductModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var productId = button.data('product-id');
        var modal = $(this);
        modal.find('#restockProductId').val(productId);
    });

    $('#restockProductForm').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: 'controller/restock_product.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#statusModalBody').text(response.message);
                $('#statusModal').modal('show');
                $('#restockProductModal').modal('hide');
                location.reload();
            },
            error: function(xhr, status, error) {
                alert('An error occurred while restocking the product.');
            }
        });
    });
});
      </script>

    <script>
$(document).ready(function() {
    $('#deleteConfirmationModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var productId = button.data('product-id'); // Extract product ID from data-* attributes
        var modal = $(this);
        modal.find('#deleteProductId').val(productId);
    });

    $('#confirmDeleteBtn').click(function () {
        var productId = $('#deleteProductId').val();
        $.ajax({
            url: 'controller/delete_product.php',
            type: 'POST',
            data: { id: productId },
            success: function(response) {
                // Handle the response from the server
                // For example, reload the page to see the changes
                location.reload();
            },
            error: function(xhr, status, error) {
                // Handle errors
                alert('An error occurred while deleting the product.');
            }
        });
    });
});
</script>
    <script>
    $('#updateProductModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var productId = button.data('product-id');
        var productName = button.data('product-name');
        var productCategory = button.data('product-category');
        var productPrice = button.data('product-price');
        var productStock = button.data('product-stock');
        

        var modal = $(this);
        modal.find('#updateProductId').val(productId);
        modal.find('#updateProductName').val(productName);
        modal.find('#updateProductCategory').val(productCategory);
        modal.find('#updatePrice').val(productPrice);
        modal.find('#updateProductStock').val(productStock);
    });
</script>

<script>
        window.addEventListener('DOMContentLoaded', (event) => {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            if (status) {
                const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
                const statusModalBody = document.getElementById('statusModalBody');
                if (status === 'success') {
                    statusModalBody.textContent = 'Product updated successfully!';
                } else if (status === 'error') {
                    statusModalBody.textContent = 'Error updating product.';
                }
                statusModal.show();
            }
        });
    </script>

 
</body>
</html>

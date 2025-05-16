<?php include("layouts/header.php"); ?>
<div class="dashboard-ecommerce">
    <div class="container-fluid dashboard-content ">
        <!-- ============================================================== -->
        <!-- pageheader  -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Blossom Box</h2>
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Admin</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Products</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end pageheader  -->
        <!-- ============================================================== -->
        <div class="d-flex justify-content-end">
            <form class="d-flex align-items-center" method="GET" action="">
                <select class="form-control mr-1" name="sort" onchange="this.form.submit()">
                    <option <?php if (isset($_GET['sort']) && $_GET['sort'] == 'asc') echo 'selected'; ?> value="asc">Asc</option>
                    <option <?php if (isset($_GET['sort']) && $_GET['sort'] == 'desc') echo 'selected'; ?> value="desc">Desc</option>

                </select>
                <select class="form-control mr-1" name="limit" onchange="this.form.submit()">
                    <option <?php if (isset($_GET['limit']) && $_GET['limit'] == '5') echo 'selected'; ?> value="5">5</option>
                    <option <?php if (isset($_GET['limit']) && $_GET['limit'] == '10') echo 'selected'; ?> value="10">10</option>
                    <option <?php if (isset($_GET['limit']) && $_GET['limit'] == '20') echo 'selected'; ?> value="20">20</option>
                    <option <?php if (isset($_GET['limit']) && $_GET['limit'] == '30') echo 'selected'; ?> value="30">30</option>
                    <option <?php if (isset($_GET['limit']) && $_GET['limit'] == '50') echo 'selected'; ?> value="50">50</option>
                </select>
            </form>
            <div class="dropdown ms-auto">

                <button class="btn btn-primary btn-icon" data-toggle="modal" data-target="#addProduct">
                    <i class="bi bi-plus-circle"></i> Add Product
                </button>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Product Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            <tbody>
                <?php
                // Assuming you have sanitized the input to prevent SQL injection
                $search = isset($_GET['search']) ? $_GET['search'] : '';

                $total_rows_sql = "SELECT COUNT(*) AS total FROM products";
                if (!empty($search)) {
                    // Modify the total_rows_sql to include the search filter
                    $total_rows_sql .= " WHERE prod_name LIKE '%$search%'"; // Modify this according to your search criteria
                }

                $total_rows_result = mysqli_query($conn, $total_rows_sql);
                $total_rows = mysqli_fetch_assoc($total_rows_result)['total'];

                $limit = isset($_GET['limit']) ? $_GET['limit'] : 5;
                $sorting = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';
                $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

                $pages = ceil($total_rows / $limit);
                $offset = ($current_page - 1) * $limit;

                // Sort by prod_no
                $sort_column = 'status';
                $sort_order = ($sorting == 'desc') ? 'DESC' : 'ASC';

                $sql = "SELECT products.*, products.category_id AS cat_id, category.category_id, category.category_name FROM products 
        INNER JOIN category ON products.category_id = category.category_id ";

                if (!empty($search)) {
                    // Add WHERE clause for search if a search query is provided
                    $sql .= " WHERE prod_name LIKE '%$search%'"; // Modify this according to your search criteria
                }

                $sql .= " ORDER BY $sort_column $sort_order
          LIMIT $limit OFFSET $offset";

                $result = mysqli_query($conn, $sql);

                $table_rows = '';
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $modal_id = 'editItem_' . $row['prod_no'];
                ?>

                        <tr>
                            <td>#<?= $row['prod_no'] ?></td>
                            <td>
                                <div class="m-r-10"><img src="../prodimg/<?= $row['prod_img'] ?>" alt="user" class="rounded" width="45"></div>
                            </td>
                            <td><?= $row['prod_name'] ?></td>
                            <td><?= $row['description'] ?></td>
                            <td><?= $row['category_name'] ?></td>
                            <td>₱ <?= $row['price'] ?></td>
                            <td><?= $row['stock'] ?? 0 ?></td>
                            <td><?= date('F j, Y', strtotime($row['product_date'])) ?></td>
                            <td><?= $row['status'] ?></td>
                            <td class="text-center d-flex"><button class="btn btn-primary me-1" data-toggle="modal" data-target="#<?= $modal_id ?>">Edit</button>
                                <form method="post" action="" class="delete-form"><input type="hidden" name="delete" value="<?= $row['prod_no'] ?>"><button type="submit" class="btn btn-secondary" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button></form>
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="<?= $modal_id ?>" tabindex="-1" aria-labelledby="<?= $modal_id ?>Label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="<?= $modal_id ?>">Edit Product</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <form method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="prod_no" value="<?= $row['prod_no'] ?>">
                                        <input type="hidden" name="existing_image" value="<?= $row['prod_img'] ?>">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="prodname<?= $modal_id ?>" class="col-form-label">Product Name</label>
                                                <input id="prodname<?= $modal_id ?>" type="text" class="form-control" name="prod_name" value="<?= $row['prod_name'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="description<?= $modal_id ?>">Description</label>
                                                <textarea class="form-control" id="description<?= $modal_id ?>" rows="3" name="desc"><?= $row['description'] ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="price<?= $modal_id ?>" class="col-form-label">Price</label>
                                                <input id="price<?= $modal_id ?>" type="number" class="form-control" placeholder="Price" name="price" value="<?= $row['price'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="stock<?= $modal_id ?>" class="col-form-label">Stock</label>
                                                <input id="stock<?= $modal_id ?>" type="number" class="form-control" placeholder="Stock" name="stock" value="<?= $row['stock'] ?? 0 ?>" min="0">
                                            </div>
                                            <div class="form-group">
                                                <label for="proddate<?= $modal_id ?>">Product Date</label>
                                                <input id="proddate<?= $modal_id ?>" type="date" placeholder="Date" class="form-control" name="proddate" value="<?= $row['product_date'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="category<?= $modal_id ?>">Category</label>
                                                <select class="form-control" id="category<?= $modal_id ?>" name="category">
                                                    <option disabled selected>--- Select Category ---</option>
                                                    <?php
                                                    $sql1 = "SELECT * FROM category";
                                                    $result1 = mysqli_query($conn, $sql1);

                                                    if ($result1) {
                                                        if (mysqli_num_rows($result1) > 0) {
                                                            while ($row1 = mysqli_fetch_assoc($result1)) {
                                                                echo '<option value="' . $row1['category_id'] . '"';
                                                                if ($row1['category_id'] == $row['category_id']) {
                                                                    echo ' selected';
                                                                }
                                                                echo '>' . $row1['category_name'] . '</option>';
                                                            }
                                                        } else {
                                                            echo '<option disabled>No categories found</option>';
                                                        }
                                                    } else {
                                                        echo '<option disabled>Error fetching categories</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="status<?= $modal_id ?>">Status</label>
                                                <select class="form-control" id="status<?= $modal_id ?>" name="status">
                                                    <option disabled selected>--- Select Status ---</option>
                                                    <option value="Available" <?= $row['status'] == 'Available' ? 'selected' : '' ?>>Available</option>
                                                    <option value="Unavailable" <?= $row['status'] == 'Unavailable' ? 'selected' : '' ?>>Unavailable</option>
                                                </select>
                                            </div>
                                            <div class="custom-file mb-3">
                                                <input type="file" class="custom-file-input" id="productImages<?= $modal_id ?>" name="photo" accept="image/*" onchange="previewImage<?= $modal_id ?>(event)">
                                                <label class="custom-file-label" for="customFile<?= $modal_id ?>">Image Input</label>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <img id="imagePreview<?= $modal_id ?>" src="../prodimg/<?= $row['prod_img'] ?>" class="img-thumbnail" alt="Image Preview">
                                            </div>
                                            <script>
                                                function previewImage<?= $modal_id ?>(event) {
                                                    var input = event.target;
                                                    var imagePreview = document.getElementById('imagePreview<?= $modal_id ?>');

                                                    if (input.files && input.files[0]) {
                                                        var reader = new FileReader();
                                                        reader.onload = function(e) {
                                                            imagePreview.src = e.target.result;
                                                            imagePreview.style.display = 'block';
                                                        }
                                                        reader.readAsDataURL(input.files[0]);
                                                    } else {
                                                        imagePreview.style.display = 'none';
                                                    }
                                                }
                                            </script>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="update" class="btn btn-primary">Apply Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                <?php
                    }
                } else {
                    echo '<tr><td colspan="9" class="text-center">No data available</td></tr>';
                }
                ?>

            </tbody>

        </table>
    </div>


    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php echo $current_page == 1 ? 'disabled' : ''; ?>">
                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $current_page - 1])); ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            
            <?php
            // Show limited page numbers with ellipsis
            $max_visible_pages = 5; // Maximum number of page numbers to show
            $half = floor($max_visible_pages / 2);
            
            // Calculate start and end page numbers to display
            $start_page = max(1, $current_page - $half);
            $end_page = min($pages, $start_page + $max_visible_pages - 1);
            
            // Adjust start page if we're near the end
            if ($end_page - $start_page + 1 < $max_visible_pages) {
                $start_page = max(1, $end_page - $max_visible_pages + 1);
            }
            
            // Show first page and ellipsis if needed
            if ($start_page > 1) {
                echo '<li class="page-item"><a class="page-link" href="?' . http_build_query(array_merge($_GET, ['page' => 1])) . '">1</a></li>';
                if ($start_page > 2) {
                    echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                }
            }
            
            // Show page numbers
            for ($i = $start_page; $i <= $end_page; $i++) {
                echo '<li class="page-item ' . ($current_page == $i ? 'active' : '') . '">';
                echo '<a class="page-link" href="?' . http_build_query(array_merge($_GET, ['page' => $i])) . '">' . $i . '</a>';
                echo '</li>';
            }
            
            // Show last page and ellipsis if needed
            if ($end_page < $pages) {
                if ($end_page < $pages - 1) {
                    echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                }
                echo '<li class="page-item"><a class="page-link" href="?' . http_build_query(array_merge($_GET, ['page' => $pages])) . '">' . $pages . '</a></li>';
            }
            ?>
            
            <li class="page-item <?php echo $current_page == $pages ? 'disabled' : ''; ?>">
                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $current_page + 1])); ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
<!-- Modal -->
<div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="addProductLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductLabel">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="prodname" class="col-form-label">Product Name</label>
                        <input id="prodname" type="text" class="form-control" name="prod_name">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" rows="3" name="desc"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-form-label">Price</label>
                        <input id="price" type="number" class="form-control" placeholder="Price" name="price">
                    </div>
                    <div class="form-group">
                        <label for="stock" class="col-form-label">Stock</label>
                        <input id="stock" type="number" class="form-control" placeholder="Stock" name="stock" min="0">
                    </div>
                    <div class="form-group">
                        <label for="proddate">Product Date</label>
                        <input id="proddate" type="date" placeholder="Date" class="form-control" name="proddate">
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control" id="category" name="category">
                            <option disabled selected>--- Select Category ---</option>
                            <?php
                            $sql = "SELECT * FROM category";
                            $result = mysqli_query($conn, $sql);

                            if ($result) {
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                                    }
                                } else {
                                    echo '<option disabled>No categories found</option>';
                                }
                            } else {
                                echo '<option disabled>Error fetching categories</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="form-control" id="status" name="status">
                                                    <option disabled selected>--- Select Status ---</option>
                                                    <option value="Available">Available</option>
                                                    <option value="Unavailable">Unavailable</option>
                                                </select>
                                            </div>
                    <div class="custom-file mb-3">
                        <input type="file" class="custom-file-input" id="productImages" name="photo" accept="image/*" onchange="previewImage(event)">
                        <label class="custom-file-label" for="customFile">Image Input</label>
                    </div>
                    <div class="d-flex justify-content-center">
                        <img id="imagePreview" class="img-thumbnail" alt="Image Preview" style="display: none;">
                    </div>
                    <script>
                        function previewImage(event) {
                            var input = event.target;
                            var imagePreview = document.getElementById('imagePreview');

                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    imagePreview.src = e.target.result;
                                    imagePreview.style.display = 'block';
                                }
                                reader.readAsDataURL(input.files[0]);
                            } else {
                                imagePreview.style.display = 'none';
                            }
                        }
                    </script>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
if (isset($_POST['save'])) {

    // Check if a file is uploaded
    if ($_FILES['photo']['name'] != '') {
        // File uploaded, handle file processing
        $file_name = $_FILES['photo']['name'];
        $file_temp = $_FILES['photo']['tmp_name'];
        $file_type = $_FILES['photo']['type'];
        $file_size = $_FILES['photo']['size'];
        $file_error = $_FILES['photo']['error'];

        // Move uploaded file to desired location
        $upload_dir = '../prodimg/'; // Adjust directory path as necessary
        $target_file = $upload_dir . basename($file_name);

        // Move the file to the uploads directory
        if (move_uploaded_file($file_temp, $target_file)) {
            $prod_img = basename($file_name);
        } else {
            // Handle file upload error
            echo '<script>alert("Sorry, there was an error uploading your file."); window.location.replace("products.php");</script>';
            exit; // Terminate script execution
        }
    } else {
        $prod_img = "default.jpg";
    }

    // Retrieve other form data
    $prod_name = mysqli_real_escape_string($conn, $_POST['prod_name']);
    $desc = mysqli_real_escape_string($conn, $_POST['desc']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $cat = mysqli_real_escape_string($conn, $_POST['category']);
    $date = mysqli_real_escape_string($conn, $_POST['proddate']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "INSERT INTO `products`(`prod_name`, `description`, `price`, `stock`, `product_date`, `category_id`, `prod_img`, `status`, `created_at`) VALUES ('$prod_name', '$desc', '$price', '$stock', '$date','$cat', '$prod_img', '$status', NOW())";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'success',
            title: 'New Flower inserted successfully.',
        });
    </script>";
        echo '<meta http-equiv="refresh" content="0;url=products.php">';
        exit();
    } else {
        echo '<script>alert("Error: ' . $sql . '<br>' . mysqli_error($conn) . '"); window.location.replace("products.php");</script>';
    }

    mysqli_close($conn);
}

if (isset($_POST['update'])) {
    // Retrieve form data
    $prod_no = $_POST['prod_no'];
    $prod_name = mysqli_real_escape_string($conn, $_POST['prod_name']);
    $desc = mysqli_real_escape_string($conn, $_POST['desc']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $cat = mysqli_real_escape_string($conn, $_POST['category']);
    $date = mysqli_real_escape_string($conn, $_POST['proddate']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    if ($_FILES['photo']['name'] != '') {
        $file_name = $_FILES['photo']['name'];
        $file_temp = $_FILES['photo']['tmp_name'];
        $file_type = $_FILES['photo']['type'];
        $file_size = $_FILES['photo']['size'];
        $file_error = $_FILES['photo']['error'];

        $upload_dir = '../profile_img/';
        $target_file = $upload_dir . basename($file_name);

        if (move_uploaded_file($file_temp, $target_file)) {
            $prod_img = basename($file_name);
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    } else {
        $prod_img = $_POST['existing_image'];
    }
    // `products`(`prod_no`, `prod_name`, `description`, `price`, `quantity`, `product_date`, `category_id`, `prod_img`)
    $sql = "UPDATE products SET prod_name = '$prod_name', `description` = '$desc', `price` = '$price', `stock` = '$stock', `product_date` = '$date', `category_id` = '$cat', `prod_img` = '$prod_img', status = '$status' WHERE prod_no = '$prod_no'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'success',
            title: 'Flower updated successfully.',
        });
    </script>";
        echo '<meta http-equiv="refresh" content="0;url=products.php">';
        exit();
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}
if (isset($_POST['delete'])) {
    $item_id = $_POST['delete'];
    $delete_sql = "DELETE FROM products WHERE prod_no = $item_id";
    if (mysqli_query($conn, $delete_sql)) {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'success',
            title: 'Flower deleted successfully.',
        });
    </script>";
        echo '<meta http-equiv="refresh" content="0;url=products.php">';
        exit();
    } else {
        echo '<script>alert("Error deleting item."); window.location.replace("products.php");</script>';
    }
}

?>
<?php include("layouts/footer.php"); ?>
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
                                <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Incharge</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Orders</li>
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
                <select class="form-control" name="status" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Pending') echo 'selected'; ?> value="Pending">Pending</option>
                    <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Order Confirmed') echo 'selected'; ?> value="Order Confirmed">Order Confirmed</option>
                    <option <?php if (isset($_GET['status']) && $_GET['status'] == 'On the Way') echo 'selected'; ?> value="On the Way">On the Way</option>
                    <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Delivered') echo 'selected'; ?> value="Delivered">Delivered</option>
                    <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Completed') echo 'selected'; ?> value="Completed">Completed</option>
                    <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Cancelled') echo 'selected'; ?> value="Cancelled">Cancelled</option>
                </select>
            </form>
            <div class="dropdown ms-auto">

            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Full Name</th>
                    <th>Total</th>
                    <th>Payment Method</th>
                    <th>Date</th>
                    <th>Order Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $status = isset($_GET['status']) ? $_GET['status'] : '';

                // Calculate total rows
                $total_rows_sql = "
    SELECT COUNT(DISTINCT orders.order_id) AS total 
    FROM orders
    INNER JOIN userinfo ON orders.userid = userinfo.userid
";
                $where_clauses = [];

                if (!empty($search)) {
                    $where_clauses[] = "(
        orders.order_id LIKE '%$search%' OR 
        CONCAT(userinfo.fname, ' ', userinfo.lname) LIKE '%$search%'
    )";
                }

                if (!empty($status)) {
                    $where_clauses[] = "orders.status = '$status'";
                }

                if (!empty($where_clauses)) {
                    $total_rows_sql .= " WHERE " . implode(' AND ', $where_clauses);
                }

                $total_rows_result = mysqli_query($conn, $total_rows_sql);
                $total_rows = mysqli_fetch_assoc($total_rows_result)['total'];

                $limit = isset($_GET['limit']) ? $_GET['limit'] : 5;
                $sorting = isset($_GET['sort']) ? $_GET['sort'] : 'asc';
                $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

                $pages = ceil($total_rows / $limit);
                $offset = ($current_page - 1) * $limit;

                // Sort by order_id, order_datetime, and custom status order
                $sort_order = ($sorting == 'asc') ? 'asc' : 'desc';

                // Retrieve orders with buyer's full name, total amount, payment method, order date, and status
                $sql = "
                        SELECT 
                            orders.order_id, 
                            CONCAT(userinfo.fname, ' ', userinfo.lname) AS fullname, 
                            payment.amount AS total_amount, 
                            payment.payment_type, 
                            orders.order_datetime, 
                            orders.status 
                        FROM 
                            orders 
                        INNER JOIN 
                            userinfo 
                        ON 
                            orders.userid = userinfo.userid 
                        INNER JOIN 
                            payment 
                        ON 
                            orders.order_id = payment.order_id 
                        ";

                                        if (!empty($where_clauses)) {
                                            $sql .= " WHERE " . implode(' AND ', $where_clauses);
                                        }

                                        $sql .= "
                            GROUP BY orders.order_id
                            ORDER BY 
                                FIELD(orders.status, 'Pending', 'Order Confirmed', 'On the Way', 'Delivered', 'Completed', 'Cancelled') $sort_order,
                                orders.order_datetime desc
                            LIMIT $limit OFFSET $offset
                        ";

                // echo $sql;
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $order_id = $row['order_id'];
                        $fullname = $row['fullname'];
                        $total_amount = $row['total_amount'];
                        $payment_type = $row['payment_type'];
                        $order_datetime = date('F j, Y \a\t g:iA', strtotime($row['order_datetime']));
                        $status = $row['status'];
                        $modal_id = 'updateOrder_' . $order_id;
                ?>
                        <tr>
                            <td><a href="#"><?= $order_id ?></a></td>
                            <td><?= $fullname ?></td>
                            <td>₱<?= number_format($total_amount, 2) ?></td>
                            <td><?= $payment_type ?></td>
                            <td><?= $order_datetime ?></td>
                            <td><?= $status ?></td>
                            <td class="text-center d-flex">
                                <button class="btn btn-success me-1" data-toggle="modal" data-target="#<?= $modal_id ?>" <?= $status == 'Completed' || $status == 'Cancelled' ? 'disabled' : '' ?>>Update</button>
                            </td>
                        </tr>

                        <!-- Modal for updating order -->
                        <div class="modal fade" id="<?= $modal_id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Update Order #<?= $order_id ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <form method="post" action="">
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Order Status</label>
<select class="form-control" name="status">
                                                    <option value="Pending" <?= $status == 'Pending' ? 'selected' : '' ?> <?= $status == 'Order Confirmed' || $status == 'On the Way' || $status == 'Delivered' || $status == 'Cancelled' ? 'disabled' : '' ?>>Pending</option>
                                                    <option value="Order Confirmed" <?= $status == 'Order Confirmed' ? 'selected' : '' ?> <?= $status == 'On the Way' || $status == 'Delivered' || $status == 'Cancelled' ? 'disabled' : '' ?>>Order Confirmed</option>
                                                    <option value="On the Way" <?= $status == 'On the Way' ? 'selected' : '' ?> <?= $status == 'Delivered' || $status == 'Cancelled' ? 'disabled' : '' ?>>On the Way</option>
                                                    <option value="Delivered" <?= $status == 'Delivered' ? 'selected' : '' ?> <?= $status == 'Cancelled' ? 'disabled' : '' ?>>Delivered</option>
                                                    <option value="Cancelled" <?= $status == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="order_id" value="<?= $order_id ?>">
                                            <button type="submit" class="btn btn-primary" name="update_order">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="7" class="text-center">No data available</td></tr>';
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
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $update_sql = "UPDATE orders SET status = '$status' WHERE order_id = '$order_id'";
    if ($stmt = mysqli_prepare($conn, $update_sql)) {
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['alert'] = "<script>
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Order status updated successfully.',
                                });
                            </script>";
    $query_params = $_SERVER['QUERY_STRING'];
    $redirect_url = 'orders.php' . (!empty($query_params) ? '?' . $query_params : '');

    echo '<meta http-equiv="refresh" content="0;url=' . $redirect_url . '">';
            exit();
        } else {
            $_SESSION['alert'] = "<script>
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Error updating order status: " . mysqli_error($conn) . "',
                                });
                            </script>";
    $query_params = $_SERVER['QUERY_STRING'];
    $redirect_url = 'orders.php' . (!empty($query_params) ? '?' . $query_params : '');

    echo '<meta http-equiv="refresh" content="0;url=' . $redirect_url . '">';
            exit();
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['alert'] = "<script>
                            Toast.fire({
                                icon: 'success',
                                title: 'Error preparing statement: " . mysqli_error($conn) . "',
                            });
                            </script>";
    $query_params = $_SERVER['QUERY_STRING'];
    $redirect_url = 'orders.php' . (!empty($query_params) ? '?' . $query_params : '');

    echo '<meta http-equiv="refresh" content="0;url=' . $redirect_url . '">';
        exit();
    }
}
?>
<?php include("layouts/footer.php"); ?>
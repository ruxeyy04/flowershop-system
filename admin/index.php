<?php include("layouts/header.php"); ?>
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
                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Blossom Box</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end pageheader  -->
    <!-- ============================================================== -->
    <div class="ecommerce-widget">
        <?php
        $query = "SELECT COUNT(userid) AS user_count FROM userinfo";
        $result = mysqli_query($conn, $query);
        $user_count = mysqli_fetch_assoc($result)['user_count'];

        $query = "SELECT COUNT(order_id) AS order_count FROM orders";
        $result = mysqli_query($conn, $query);
        $order_count = mysqli_fetch_assoc($result)['order_count'];

        $query = "SELECT SUM(amount) AS total_sales FROM payment";
        $result = mysqli_query($conn, $query);
        $total_sales = mysqli_fetch_assoc($result)['total_sales'];

        $query = "SELECT COUNT(status) AS completed_orders FROM orders WHERE status = 'Completed'";
        $result = mysqli_query($conn, $query);
        $completed_orders = mysqli_fetch_assoc($result)['completed_orders'];

        ?>
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-muted">Total Orders</h5>
                        <div class="metric-value d-inline-block">
                            <h1 class="mb-1"><?= $order_count ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-muted">Total Sales</h5>
                        <div class="metric-value d-inline-block">
                            <h1 class="mb-1">₱ <?= number_format($total_sales, 2) ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-muted">Total Users</h5>
                        <div class="metric-value d-inline-block">
                            <h1 class="mb-1"><?= $user_count ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-muted">Completed Orders</h5>
                        <div class="metric-value d-inline-block">
                            <h1 class="mb-1"><?= $completed_orders ?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- ============================================================== -->

            <!-- ============================================================== -->

            <!-- recent orders  -->
            <!-- ============================================================== -->
            <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12 col-12">
                <div class="card">
                    <h5 class="card-header">Recent Users</h5>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Usertype</th>
                                        <th>Contact Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_rows_sql1 = "
                                        SELECT COUNT(*) AS total 
                                        FROM userinfo 
                                    ";


                                    $total_rows_result1 = mysqli_query($conn, $total_rows_sql1);
                                    $total_rows1 = mysqli_fetch_assoc($total_rows_result1)['total'];

                                    $limit1 = isset($_GET['limit']) ? $_GET['limit'] : 5;
                                    $sorting1 = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';
                                    $current_page1 = isset($_GET['pagee']) ? $_GET['pagee'] : 1;

                                    $pages1 = ceil($total_rows1 / $limit1);
                                    $offset1 = ($current_page1 - 1) * $limit1;

                                    // Sort by user id
                                    $sort_column1 = 'userinfo.userid';
                                    $sort_order1 = ($sorting1 == 'desc') ? 'DESC' : 'ASC';

                                    $sql1 = "
                                            SELECT *
                                            FROM userinfo
                                        ";

                                    $sql1 .= " ORDER BY $sort_column1 $sort_order1
                                         LIMIT $limit1 OFFSET $offset1";

                                    $result1 = mysqli_query($conn, $sql1);

                                    $table_rows1 = '';

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result1)) {
                                    ?>
                                            <tr>
                                                <td><a href="#"><?= $row['userid'] ?></a></td>
                                                <td><?= $row['fname'] . ' ' . $row['lname'] ?></td>
                                                <td><?= $row['username'] ?></td>
                                                <td><?= $row['email'] ?></td>
                                                <td><?= $row['usertype'] ?></td>
                                                <td><?= $row['contact_number'] ?></td>

                                            </tr>

                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="7" class="text-center">No data available</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php echo $current_page1 == 1 ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['pagee' => $current_page1 - 1])); ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                
                                <?php
                                // Show limited page numbers with ellipsis
                                $max_visible_pages = 5; // Maximum number of page numbers to show
                                $half = floor($max_visible_pages / 2);
                                
                                // Calculate start and end page numbers to display
                                $start_page = max(1, $current_page1 - $half);
                                $end_page = min($pages1, $start_page + $max_visible_pages - 1);
                                
                                // Adjust start page if we're near the end
                                if ($end_page - $start_page + 1 < $max_visible_pages) {
                                    $start_page = max(1, $end_page - $max_visible_pages + 1);
                                }
                                
                                // Show first page and ellipsis if needed
                                if ($start_page > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="?' . http_build_query(array_merge($_GET, ['pagee' => 1])) . '">1</a></li>';
                                    if ($start_page > 2) {
                                        echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                                    }
                                }
                                
                                // Show page numbers
                                for ($i = $start_page; $i <= $end_page; $i++) {
                                    echo '<li class="page-item ' . ($current_page1 == $i ? 'active' : '') . '">';
                                    echo '<a class="page-link" href="?' . http_build_query(array_merge($_GET, ['pagee' => $i])) . '">' . $i . '</a>';
                                    echo '</li>';
                                }
                                
                                // Show last page and ellipsis if needed
                                if ($end_page < $pages1) {
                                    if ($end_page < $pages1 - 1) {
                                        echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="?' . http_build_query(array_merge($_GET, ['pagee' => $pages1])) . '">' . $pages1 . '</a></li>';
                                }
                                ?>
                                
                                <li class="page-item <?php echo $current_page1 == $pages1 ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['pagee' => $current_page1 + 1])); ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- end recent orders  -->

        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12 col-12">
                <div class="card">
                    <h5 class="card-header">Recent Orders</h5>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Order#</th>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Total Items</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    // Calculate total rows
                                    $total_rows_sql = "
                        SELECT COUNT(DISTINCT orders.order_id) AS total 
                        FROM orders
                        INNER JOIN userinfo ON orders.userid = userinfo.userid
                        ";


                                    $total_rows_result = mysqli_query($conn, $total_rows_sql);
                                    $total_rows = mysqli_fetch_assoc($total_rows_result)['total'];


                                    $total_rows_result = mysqli_query($conn, $total_rows_sql);
                                    $total_rows = mysqli_fetch_assoc($total_rows_result)['total'];

                                    $limit = isset($_GET['limit']) ? $_GET['limit'] : 5;
                                    $sorting = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';
                                    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

                                    $pages = ceil($total_rows / $limit);
                                    $offset = ($current_page - 1) * $limit;

                                    // Sort by order_id
                                    $sort_column = 'order_id';
                                    $sort_order = ($sorting == 'desc') ? 'DESC' : 'ASC';

                                    $sql = "
                                    SELECT 
                                        orders.order_id,
                                        CONCAT(userinfo.fname, ' ', userinfo.lname) AS fullname,COUNT(orderdetail.order_id) AS total_items,
                                        payment.amount AS total_amount,
                                        payment.payment_type,
                                        orders.order_datetime,
                                        orders.status
                                    FROM 
                                        orders
                                    INNER JOIN 
                                        userinfo ON orders.userid = userinfo.userid
                                    INNER JOIN 
                                        payment ON orders.userid = payment.userid
                                    INNER JOIN 
                                        orderdetail  ON orders.order_id = orderdetail.order_id
                                ";



                                    $sql .= " GROUP BY orders.order_id
                            ORDER BY $sort_column $sort_order
                            LIMIT $limit OFFSET $offset";

                                    $result = mysqli_query($conn, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $order_id = $row['order_id'];
                                            $fullname = $row['fullname'];
                                            $total_amount = $row['total_amount'];
                                            $payment_type = $row['payment_type'];
                                            $order_datetime = date('F j, Y \a\t g:iA', strtotime($row['order_datetime']));
                                            $status = $row['status'];
                                            $totalitem = $row['total_items'];
                                            $modal_id = 'updateOrder_' . $order_id;
                                    ?>
                                            <tr>
                                                <td><a href="#"><?= $order_id ?></a></td>
                                                <td><?= $fullname ?></td>
                                                <td><?= $totalitem ?></td>
                                                <td><?= $total_amount ?></td>
                                                <td><?= $status ?></td>

                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="7" class="text-center">No data available</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
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
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>
<?php include("layouts/footer.php"); ?>
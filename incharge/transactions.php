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
                                <li class="breadcrumb-item active" aria-current="page">Transaction</li>
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

        </div>
    </div>

    <div class="table-responsive">
        <table class="table lms_table_active">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Full Name</th>
                    <th>Payment Method</th>
                    <th>Order ID</th>
                    <th>Status</th>
                    <th>Payment Date</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $search = isset($_GET['search']) ? $_GET['search'] : '';

                $total_rows_sql = "
                            SELECT COUNT(*) AS total 
                            FROM payment 
                            INNER JOIN userinfo ON payment.userid = userinfo.userid
                            ";
                if (!empty($search)) {
                    $total_rows_sql .= " WHERE userinfo.username LIKE '%$search%' OR CONCAT(userinfo.fname, ' ', userinfo.lname) LIKE '%$search%'";
                }

                $total_rows_result = mysqli_query($conn, $total_rows_sql);
                $total_rows = mysqli_fetch_assoc($total_rows_result)['total'];

                $limit = isset($_GET['limit']) ? $_GET['limit'] : 5;
                $sorting = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';
                $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

                $pages = ceil($total_rows / $limit);
                $offset = ($current_page - 1) * $limit;

                // Sort by payment id
                $sort_column = 'payment.pay_id';
                $sort_order = ($sorting == 'desc') ? 'DESC' : 'ASC';

                $sql = "
                                SELECT payment.*, userinfo.username, userinfo.fname, userinfo.lname, orders.order_id, orders.status 
                                FROM payment 
                                INNER JOIN userinfo ON payment.userid = userinfo.userid
                                INNER JOIN orders ON payment.order_id = orders.order_id
                            ";

                if (!empty($search)) {
                    // Add WHERE clause for search if a search query is provided
                    $sql .= " WHERE userinfo.username LIKE '%$search%' OR CONCAT(userinfo.fname, ' ', userinfo.lname) LIKE '%$search%'";
                }

                $sql .= " ORDER BY $sort_column $sort_order
                            LIMIT $limit OFFSET $offset";

                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <tr>
                            <td><a href="#"><?= $row['pay_id'] ?></a></td>
                            <td>
                                <?= $row['fname'] . ' ' . $row['lname'] ?>
                            </td>
                            <td><?= $row['payment_type'] ?></td>
                            <td><?= $row['order_id'] ?></td>
                            <td><?= $row['status'] ?></td>
                            <td><?= date('F j, Y', strtotime($row['payment_date'])) ?></td>
                            <td><?= $row['amount'] ?></td>
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
<!-- Add User Modal -->
<div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-floating mb-4">
                        <input type="text" name="fname" class="form-control form-control-sm" required />
                        <label for="fname">First Name</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="text" name="lname" class="form-control form-control-sm" required />
                        <label for="lname">Last Name</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="text" name="username" class="form-control form-control-sm" required />
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="email" name="email" class="form-control form-control-sm" required />
                        <label for="email">Email</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="text" name="contact_number" class="form-control form-control-sm" required />
                        <label for="contact_number">Contact Number</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" name="password" class="form-control form-control-sm" required />
                        <label for="password">Password</label>
                    </div>
                    <div class="form-floating mb-4">
                        <select class="form-control form-control-sm" name="gender" id="gender" required>
                            <option disabled selected>--- Select Gender ---</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        <label for="gender">Gender</label>
                    </div>
                    <div class="form-floating mb-4">
                        <select class="form-control form-control-sm" name="usertype" id="usertype" required>
                            <option disabled selected>--- Select Usertype ---</option>
                            <option value="Client">Client</option>
                            <option value="Incharge">Incharge</option>
                            <option value="Admin">Admin</option>
                        </select>
                        <label for="usertype">Usertype</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    // Connect to the database (make sure $conn is defined somewhere before this block)
    // $conn = new mysqli($servername, $username, $password, $dbname);

    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']); // Add contact number
    $gender = mysqli_real_escape_string($conn, $_POST['gender']); // Add gender
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $usertype = mysqli_real_escape_string($conn, $_POST['usertype']);


    // Insert into userinfo table
    $insert_info_query = "
        INSERT INTO userinfo (fname, lname, username, email, contact_number, gender, password, usertype) 
        VALUES ('$fname', '$lname', '$username', '$email', '$contact_number', '$gender', '$password', '$usertype')
    ";

    // Execute the query
    if (mysqli_query($conn, $insert_info_query)) {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'success',
            title: 'New user added successfully!',
        });
    </script>";
        echo '<meta http-equiv="refresh" content="0;url=users.php">';
        exit();
    } else {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'error',
            title: 'Error adding user info: " . mysqli_error($conn) . "',
        });
    </script>";
        echo '<meta http-equiv="refresh" content="0;url=users.php">';
        exit();
    }
}



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $userid = mysqli_real_escape_string($conn, $_POST['userid']);
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $midname = mysqli_real_escape_string($conn, $_POST['midname']); // Add midname
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']); // Add contact number
    $gender = mysqli_real_escape_string($conn, $_POST['gender']); // Add gender
    $usertype = mysqli_real_escape_string($conn, $_POST['usertype']);
    $resetpassword = mysqli_real_escape_string($conn, $_POST['resetpassword']);

    // Construct the update query for userinfo
    $update_info_query = "
        UPDATE userinfo 
        SET fname = '$fname', midname = '$midname', lname = '$lname', username = '$username', 
            email = '$email', contact_number = '$contact_number', gender = '$gender', usertype = '$usertype'";

    // If resetpassword is provided, include it in the update query without hashing
    if (!empty($resetpassword)) {
        $update_info_query .= ", password = '$resetpassword'";
    }

    $update_info_query .= " WHERE userid = '$userid'";

    // Execute the query
    if (mysqli_query($conn, $update_info_query)) {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'success',
            title: 'User information updated successfully!',
        });
    </script>";
        echo '<meta http-equiv="refresh" content="0;url=users.php">';
        exit();
    } else {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'error',
            title: 'Error updating user information: " . mysqli_error($conn) . "',
        });
    </script>";
        echo '<meta http-equiv="refresh" content="0;url=users.php">';
        exit();
    }
}
?>
<?php include("layouts/footer.php"); ?>
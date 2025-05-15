<?php include('layouts/header.php') ?>
<!-- Breadcrumb Start -->
<div class="breadcrumb-section">
    <div class="container-fluid custom-container">
        <div class="breadcrumb-wrapper text-center">
            <h2 class="breadcrumb-wrapper__title">My Order</h2>
            <ul class="breadcrumb-wrapper__items justify-content-center">
                <li><a href="/">Home</a></li>
                <li><span>My Order</span></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->
<div class="container">
            <form action="" method="get">
            <div class="row d-flex justify-content-center">
                <div class="col-md-4">
                    <select class="form-select" name="status" onchange="this.form.submit()">
                        <option value="">All</option>
                        <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Pending') echo 'selected'; ?> value="Pending">Pending</option>
                        <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Order Confirmed') echo 'selected'; ?> value="Order Confirmed">Order Confirmed</option>
                        <option <?php if (isset($_GET['status']) && $_GET['status'] == 'On the Way') echo 'selected'; ?> value="On the Way">On the Way</option>
                        <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Delivered') echo 'selected'; ?> value="Delivered">Delivered</option>
                        <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Completed') echo 'selected'; ?> value="Completed">Completed</option>
                        <option <?php if (isset($_GET['status']) && $_GET['status'] == 'Cancelled') echo 'selected'; ?> value="Cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
        </form>
    <div class="my-account-orders">
        <div class="my-account-table table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <span>Order</span>
                        </th>
                        <th>
                            <span>Date</span>
                        </th>
                        <th>
                            <span>Status</span>
                        </th>
                        <th>
                            <span>Total</span>
                        </th>
                        <th>
                            <span>Actions</span>
                        </th>
                    </tr>
                </thead>
                <?php
                if (isset($_SESSION['userid'])) {
                            $status = isset($_GET['status']) ? $_GET['status'] : '';

                            // Initialize the base query and parameters array
                            $base_query = "SELECT 
                orders.order_id,
                orders.order_datetime,
                orders.status,
                COUNT(orderdetail.order_id) AS item_count,
                SUM(orderdetail.price_each) AS total_price
            FROM 
                orders 
            JOIN 
                orderdetail 
            ON 
                orders.order_id = orderdetail.order_id 
            WHERE 
                orders.userid = ?
            ";
                            $params = [$userid];
                            $param_types = "i"; // "i" for integer (user_id)

                            // Check if status is set and update the query and parameters accordingly
                            if (!empty($status)) {
                                $base_query .= " AND orders.status = ?";
                                $params[] = $status;
                                $param_types .= "s"; // "s" for string (status)
                            }

                            // Append the order by clause
                            $base_query .= " GROUP BY 
                orders.order_id, 
                orders.order_datetime, 
                orders.status ORDER BY orders.status DESC, orders.order_datetime DESC";
                            // echo $base_query;
                            // Prepare the statement
                            $stmt = $conn->prepare($base_query);

                            // Bind the parameters
                            $stmt->bind_param($param_types, ...$params);

                            // Execute the query
                            $stmt->execute();
                            $cart_result = $stmt->get_result();
                ?>

                    <tbody>
                        <?php while ($row = $cart_result->fetch_assoc()) {
                            $order_id = $row['order_id'];
                            $order_datetime = $row['order_datetime']; // Example: '2023-05-26 13:00:00'
                            $timestamp = strtotime($order_datetime);
                            $formatted_date = date('F j, Y \a\t g:ia', $timestamp);
                            $status = $row['status'];
                            $item_count = $row['item_count'];
                            $total_price = number_format($row['total_price'], 2);
                         ?>
                            <tr>
                                <td>
                                    <a href="#">#<?= $order_id ?></a>
                                </td>
                                <td>
                                    <time><?= $formatted_date ?></time>
                                </td>
                                <td><?= $status ?></td>
                                <td>
                                    <span>₱<?= $total_price ?></span>
                                    for <?= $item_count ?> items
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#track<?= $row['order_id'] ?>">Info</button>
                                        <?php
                                        if ($row['status'] == 'Pending') { ?>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete<?= $row['order_id'] ?>">Cancel</button>
                                        <?php   }
                                        ?>
                                        <?php
                                        if ($row['status'] == 'Delivered') { ?>
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmdelivered<?= $row['order_id'] ?>">Order Confirm</button>
                                        <?php   }
                                        ?>
                                    </div>
                                    <div class="modal fade" id="delete<?= $row['order_id'] ?>" tabindex="-1" aria-labelledby="confirmdeliveredLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Cancel Order?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="" method="post">
                                                    <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                                                    <div class="modal-body">
                                                        Are you sure you want to cancel Order #<?= $row['order_id'] ?>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-danger" name="cancel_order">Cancel Order</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="confirmdelivered<?= $row['order_id'] ?>" tabindex="-1" aria-labelledby="confirmdeliveredLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confirmdeliveredLabel">Delivery Confirmation?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="" method="post">
                                                    <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                                                    <div class="modal-body">
                                                        Confirm Delivered Order #<?= $row['order_id'] ?>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-danger" name="confirm_order">Confirm Order</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <div class="modal fade" id="track<?= $row['order_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Order #<?= $row['order_id'] ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <?php
                                            $order_id =  $row['order_id'];
                                            $query = "SELECT o.order_id, o.order_datetime, o.status, i.prod_name, i.price,od.price_each 
                                                        FROM orders o
                                                        JOIN orderdetail od ON o.order_id = od.order_id
                                                        JOIN products i ON od.prod_no = i.prod_no
                                                        WHERE o.order_id = '$order_id'";
                                            // echo $query;
                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            $order = $result->fetch_assoc();

                                            $formatted_date = date('F d, Y', strtotime($order['order_datetime']));

                                            $status = $order['status'];
                                            $ordered_class = $preparing_class = $on_the_way_class = $delivered_class = '';
                                            if ($status == 'Pending') {
                                                $ordered_class = 'active';
                                            } elseif ($status == 'Order Confirmed') {
                                                $ordered_class = $preparing_class = 'active';
                                            } elseif ($status == 'On the Way') {
                                                $ordered_class = $preparing_class = $on_the_way_class = 'active';
                                            } elseif ($status == 'Delivered' || $status == 'Completed') {
                                                $ordered_class = $preparing_class = $on_the_way_class = $delivered_class = 'active';
                                            }

                                            // Fetch all items for the order
                                            $query_items = "SELECT i.*, od.price_each, od.quantity AS order_quantity
                                            FROM orderdetail od 
                                            JOIN products i ON od.prod_no = i.prod_no 
                                            WHERE od.order_id = '$order_id'";
                                            $stmt_items = $conn->prepare($query_items);
                                            // $stmt_items->bind_param("i", $order_id);
                                            $stmt_items->execute();
                                            $items_result = $stmt_items->get_result();
                                            ?>
                                            <div class="card1-body">
                                                <article class="card1 border-0">
                                                    <div class="card1-body row">
                                                        <div class="col"> <strong>Status:</strong> <br> <?= $row['status'] ?> </div>
                                                        <div class="col"> <strong>Order #:</strong> <br> <?= $row['order_id'] ?> </div>
                                                    </div>
                                                </article>
                                                <div class="track">
                                                    <div class="step <?= $ordered_class ?>"> <span class="icon"> <i class="fa fa-spinner"></i> </span> <span class="text">Pending</span> </div>
                                                    <div class="step <?= $preparing_class ?>"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">
                                                            Order Confirmed</span> </div>
                                                    <div class="step  <?= $on_the_way_class ?>"> <span class="icon"> <i class="fa fa-road"></i> </span> <span class="text"> On the
                                                            way </span> </div>
                                                    <div class="step <?= $delivered_class ?>"> <span class="icon"> <i class="fa fa-archive"></i> </span> <span class="text">Ready to Receive</span> </div>
                                                </div>
                                                <hr>
                                                <ul class="row">
                                                    <?php while ($item = $items_result->fetch_assoc()) {
                                                    // echo json_encode($item);
                                                    ?>
                                                    
                                                        <li class="col-md-4">
                                                            <figure class="itemside mb-3">
                                                                <div class="aside"><img src="prodimg/<?= $item['prod_img'] ?>" class="img-sm border"></div>
                                                                <figcaption class="info align-self-center">
                                                                    <p class="title"><?= $item['prod_name'] ?> <br> x<?= $item['order_quantity'] ?></p> <span class="text-muted">₱<?= number_format($item['price_each'], 2) ?>
                                                                    </span>
                                                                    <br>
                                                                </figcaption>
                                                            </figure>
                                                        </li>
                                                    <?php } ?>

                                                </ul>
                                                <hr>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </tbody>
                <?php
                } else {
                    echo '<meta http-equiv="refresh" content="0;url=login-register.php">';
                    exit;
                }
                ?>
            </table>

        </div>
    </div>
</div>
<?php
if (isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id'];

    $query = "SELECT status FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if ($order['status'] == 'Pending') {
        $update_query = "UPDATE orders SET status = 'Cancelled' WHERE order_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("i", $order_id);
        $update_stmt->execute();
        $_SESSION['alert'] = "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Cancelled',
                    text: 'Order #$order_id has been canceled.',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/orders.php';
                    }
                });
            </script>";
    } else {
        $_SESSION['alert'] = "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Order #$order_id cannot be canceled as it is already in progress.',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/orders.php';
                    }
                });
            </script>";
    }
    echo '<meta http-equiv="refresh" content="0;url=orders.php">';
    exit();
}

if (isset($_POST['confirm_order'])) {
    $order_id = $_POST['order_id'];

    // Update the order status to 'Delivered'
    $update_query = "UPDATE orders SET status = 'Completed' WHERE order_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("s", $order_id);
    $update_stmt->execute();
    $_SESSION['alert'] = "<script>
            Swal.fire({
                icon: 'success',
                title: 'Completed',
                text: 'Order #$order_id has been marked as delivered.',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/orders.php';
                }
            });
        </script>";
    echo '<meta http-equiv="refresh" content="0;url=orders.php">';
    exit();
}
?>
<?php include('layouts/footer.php') ?>
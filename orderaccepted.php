<?php include('layouts/header.php') ?>
<!-- Breadcrumb Start -->
<div class="breadcrumb-section">
    <div class="container-fluid custom-container">
        <div class="breadcrumb-wrapper text-center">
            <h2 class="breadcrumb-wrapper__title">Thank you</h2>
            <ul class="breadcrumb-wrapper__items justify-content-center">
                <li><a href="/">Home</a></li>
                <li><span>Thank you</span></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->
<?php
if (!isset($_GET['orderid'])) {
    echo '<meta http-equiv="refresh" content="0;url=flowers.php">';
    exit();
}
$order_id = $_GET['orderid'];

$sql = "SELECT * FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$order_datetime = $row['order_datetime']; // Example: '2023-05-26 13:00:00'
$timestamp = strtotime($order_datetime);
$formatted_date = date('F j, Y \a\t g:ia', $timestamp);
if (!$row) {
    echo '<meta http-equiv="refresh" content="0;url=flowers.php">';
    exit();
}

$sql1 = "SELECT a.*, b.* FROM payment a INNER JOIN billing_details b ON a.pay_id=b.pay_id WHERE a.order_id = '$order_id'";
$result1 = $conn->query($sql1);
$pay = $result1->fetch_assoc();



?>
<!-- Thank You Start -->
<div class="thank-you-section section-padding-2">
    <div class="container-fluid custom-container">
        <!-- Thank You Table Start -->
        <div class="thank-you">
            <p class="thank-you-notice">
                Thank you. Your order has been received.
            </p>

            <!-- Thank You Order Overview Start -->
            <ul class="thank-you-order-overview">
                <li>
                    <span> Order number: </span>
                    <strong><?= $order_id ?></strong>
                </li>
                <li>
                    <span> Date: </span>
                    <strong><?= $formatted_date ?></strong>
                </li>
                <li>
                    <span> Email: </span>
                    <strong><?= $pay['email'] ?></strong>
                </li>
                <li>
                    <span> Total: </span>
                    <strong>₱<?= $pay['amount'] ?></strong>
                </li>
                <li>
                    <span> Payment method: </span>
                    <strong><?= $pay['payment_type'] ?></strong>
                </li>
            </ul>
            <!-- Thank You Order Overview End -->

            <!-- Thank You Order Details Start -->
            <div class="thank-you-order-details">
                <h3 class="thank-you-title">Order details</h3>

                <table class="table">
                    <thead>
                        <tr>
                            <th class="product-name"><strong>Product</strong></th>
                            <th class="product-total">Total</th>
                        </tr>
                    </thead>
                    <?php
                    $sql2 = "SELECT a.*, a.quantity AS order_quant, b.prod_name FROM orderdetail a INNER JOIN products b ON a.prod_no=b.prod_no WHERE a.order_id = '$order_id'";
                    $result2 = $conn->query($sql2);
                    $payment = $result1->fetch_assoc();
                    ?>
                    <tbody>
                        <?php
                        while ($row = $result2->fetch_assoc()) {
                        ?>
                            <tr class="order-item">
                                <td class="product-name">
                                    <a href="#"><?= $row['prod_name'] ?></a>
                                    <strong>×&nbsp;<?= $row['order_quant'] ?></strong>
                                </td>
                                <td class="product-total">
                                    <span class="amount"> ₱<?= $row['price_each'] ?> </span>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th><strong>Subtotal</strong>:</th>
                            <td>
                                <span class="amount">₱<?= number_format($pay['amount'] - $delivery_fee, 2) ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th>Delivery fee:</th>
                            <td>
                                <span class="amount">₱<?= number_format($delivery_fee, 2) ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Payment method:</th>
                            <td><?= $pay['payment_type'] ?></td>
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td>
                                <span class="amount">₱<?= $pay['amount'] ?> </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- Thank You Order Details End -->

            <!-- Thank You Customer Details Start -->
            <div class="row">
                <div class="col-md-6">
                    <!-- Thank You Customer Billing Start -->
                    <div class="thank-you-customer-details">
                        <h3 class="thank-you-title">
                            Billing address
                        </h3>

                        <address>
                            <?= $pay['name'] ?> <br />
                            <?= $pay['address'] ?>
                            <?= $pay['city'] ?> <br />
                            <?= $pay['province'] ?> <br />
                            <?= $pay['zipcode'] ?> <br />
                            <?= $pay['phone'] ?> <br />
                            <br />

                            <?= $pay['email'] ?>
                        </address>
                    </div>
                    <!-- Thank You Customer Billing End -->
                </div>
            </div>
            <!-- Thank You Customer Details End -->
        </div>
        <!-- Thank You Table End -->
    </div>
</div>

<!-- Thank You End -->
<?php include('layouts/footer.php') ?>
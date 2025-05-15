<?php include('layouts/header.php') ?>
<!-- Breadcrumb Start -->
<div class="breadcrumb-section">
    <div class="container-fluid custom-container">
        <div class="breadcrumb-wrapper text-center">
            <h2 class="breadcrumb-wrapper__title">Checkout</h2>
            <ul class="breadcrumb-wrapper__items justify-content-center">
                <li><a href="/">Home</a></li>
                <li><span>Checkout</span></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['userid'])) {
        echo '<meta http-equiv="refresh" content="0;url=login.php">';
        exit;
    }



    // Generate the order ID
    $order_id = date('ymd') . strtoupper(bin2hex(random_bytes(4)));

    // Get cart items for the user
    $sql = "
          SELECT i.prod_no, i.price, c.quantity
          FROM carts c
          JOIN products i ON c.prod_no = i.prod_no
          WHERE c.userid = ?
      ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    $subtotal = 0;
    $cart_items = [];
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $subtotal += $row['price'] * $row['quantity'];
    }

    // Assuming free shipping
    $total = $subtotal + $delivery_fee;

    // Insert into orders table
    $order_sql = "INSERT INTO orders (order_id, userid, order_datetime, status) VALUES (?, ?, ?, 'Pending')";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("sss", $order_id, $userid, $timestamp);
    $order_stmt->execute();

    // Insert into orderdetail table
    $orderdetail_sql = "INSERT INTO orderdetail (order_id, prod_no, quantity, price_each) VALUES (?, ?, ?, ?)";
    $orderdetail_stmt = $conn->prepare($orderdetail_sql);
    foreach ($cart_items as $item) {
        $price_each = $item['price'] *  $item['quantity'];
        $orderdetail_stmt->bind_param("siii", $order_id, $item['prod_no'], $item['quantity'], $price_each);
        $orderdetail_stmt->execute();
    }

    // Insert into payment table
    $payment_sql = "INSERT INTO payment (pay_id, userid, order_id, payment_date, amount, payment_type) VALUES (?, ?, ?, ?, ?, ?)";
    $pay_id = strtoupper(bin2hex(random_bytes(4))); 
    $payment_type = $_POST['payment_method'];
    $payment_stmt = $conn->prepare($payment_sql);
    $payment_stmt->bind_param("ssssss", $pay_id, $userid, $order_id, $timestamp, $total, $payment_type);
    $payment_stmt->execute();

    $billing_sql = "INSERT INTO billing_details (billing_id, pay_id, userid,name, email, city, province, zipcode, phone, address, save_address, order_note, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $billing_id = strtoupper(bin2hex(random_bytes(4))); 
    $name = $_POST['billing_name'];
    $note = $_POST['order_note'];
    $email = $_POST['billing_email'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $zipcode = $_POST['zipcode'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $save_address = $_POST['save_address'] ?? 0;
    $billing_stmt = $conn->prepare($billing_sql);
    $billing_stmt->bind_param("sssssssssssss", $billing_id, $pay_id, $userid, $name, $email, $city, $province, $zipcode, $phone, $address, $save_address, $note, $timestamp);
    $billing_stmt->execute();


    $clear_cart_sql = "DELETE FROM carts WHERE userid = ?";
    $clear_cart_stmt = $conn->prepare($clear_cart_sql);
    $clear_cart_stmt->bind_param("i", $userid);
    $clear_cart_stmt->execute();
    
    echo '<meta http-equiv="refresh" content="0;url=orderaccepted.php?orderid=' . $order_id . '">';
    exit;
}
?>
<!-- Checkout Start -->
<div class="checkout-section section-padding-2">
    <div class="container-fluid custom-container">
        <!-- Checkout Start -->
        <?php
        $billing_sql = "SELECT *
                                            FROM billing_details 
                                            WHERE userid = ?
                                            ORDER BY date_created DESC 
                                            LIMIT 1";
      $billing_stmt = $conn->prepare($billing_sql);
      $billing_stmt->bind_param("i", $userid);
      $billing_stmt->execute();
      $billing_result = $billing_stmt->get_result();

      $billing_details = $billing_result->fetch_assoc();
      if ($billing_details) {
        if ($billing_details['save_address'] != 1) {
          unset($billing_details);
        }
      }


        ?>
        <div class="checkout-wrapper">
            <form action="" method="post">
                <div class="checkout-row">
                    <div class="checkout-col-1">
                        <!-- Checkout Details Start -->
                        <div class="checkout-details">
                            <h3 class="checkout-details__title">
                                Billing details
                            </h3>

                            <!-- Checkout Details Billing Start -->
                            <div class="checkout-details__billing">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Single Form Start -->
                                        <div class="single-form">
                                            <label class="single-form__label">Full Name *</label>
                                            <input class="single-form__input" type="text" name="billing_name" required value="<?php echo isset($billing_details['name']) ? htmlspecialchars($billing_details['name']) : $userinfo['fname'] . ' ' . $userinfo['lname']; ?>" />
                                        </div>
                                        <!-- Single Form End -->
                                    </div>
                                </div>
                                <!-- Single Form Start -->
                                <div class="single-form">
                                    <label class="single-form__label">Address address *</label>
                                    <input class="single-form__input" type="text" placeholder="House number and street name or additional info" name="address" value="<?php echo isset($billing_details['address']) ? htmlspecialchars($billing_details['address']) : ''; ?>" />
                                </div>
                                <!-- Single Form End -->
                                <!-- Single Form Start -->
                                <div class="single-form">
                                    <label class="single-form__label">City *</label>
                                    <input class="single-form__input" type="text" name="city" value="Ozamiz City" />
                                </div>
                                <!-- Single Form End -->
                                <!-- Single Form Start -->
                                <div class="single-form">
                                    <label class="single-form__label">Province *</label>
                                    <input class="single-form__input" type="text" name="province" value="Misamis Occidental" />
                                </div>
                                <!-- Single Form End -->
                                <!-- Single Form Start -->
                                <div class="single-form">
                                    <label class="single-form__label">Postcode *</label>
                                    <input class="single-form__input" type="text" name="zipcode" value="7200" />
                                </div>
                                <!-- Single Form End -->
                                <!-- Single Form Start -->
                                <div class="single-form">
                                    <label class="single-form__label">Phone *</label>
                                    <input class="single-form__input" type="text" name="phone" value="<?php echo isset($billing_details['phone']) ? htmlspecialchars($billing_details['phone']) : $userinfo['contact_number']; ?>" />
                                </div>
                                <!-- Single Form End -->
                                <!-- Single Form Start -->
                                <div class="single-form">
                                    <label class="single-form__label">Email address *</label>
                                    <input class="single-form__input" type="email" name="billing_email" value="<?php echo isset($billing_details['email']) ? htmlspecialchars($billing_details['email']) : $userinfo['email']; ?>" />
                                </div>
                                <!-- Single Form End -->
                            </div>
                            <!-- Checkout Details Billing End -->

                            <!-- Checkout Details Account Start -->
                            <div class="checkout-details__account">
                                <!-- Single Form Start -->
                                <div class="single-form">
                                    <input type="checkbox" id="account" class="account" name="save_address" value="1" <?php echo isset($billing_details['save_address']) ? 'checked' : '' ?>/>

                                    <label for="account" class="single-form__label checkbox-label"><span></span>Save this Address</label>
                                </div>
                                <!-- Single Form End -->

                            </div>
                            <!-- Checkout Details Account End -->



                            <!-- Single Form Start -->
                            <div class="single-form">
                                <label class="single-form__label">Order notes (optional)</label>
                                <textarea class="single-form__input" placeholder="Notes about your order, e.g. special notes for delivery." name="order_note"><?php echo isset($billing_details['order_note']) ? htmlspecialchars($billing_details['order_note']) : ''; ?></textarea>
                            </div>
                            <!-- Single Form End -->
                        </div>
                        <!-- Checkout Details End -->
                    </div>
                    <div class="checkout-col-2">
                        <!-- Checkout Details Start -->
                        <div class="checkout-details">
                            <h3 class="checkout-details__title">
                                Your order
                            </h3>

                            <div class="checkout-details__order-review">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="product-name">
                                                Product
                                            </th>
                                            <th class="product-total">
                                                Subtotal
                                            </th>
                                        </tr>
                                    </thead>
                                    <?php
                                    if (isset($_SESSION['userid'])) {

                                        $cart_query = "SELECT carts.*, products.*, carts.quantity AS cart_quantity
                                        FROM carts 
                                        INNER JOIN products ON carts.prod_no = products.prod_no 
                                        WHERE carts.userid = ?";
                                        $stmt = $conn->prepare($cart_query);
                                        $stmt->bind_param("i", $userid);
                                        $stmt->execute();
                                        $cart_result = $stmt->get_result();

                                        $subtotal = 0;
                                    ?>
                                        <tbody>
                                            <?php while ($row = $cart_result->fetch_assoc()) {
                                                $item_total = $row['price'] * $row['cart_quantity'];
                                                $subtotal += $item_total;
                                            ?>
                                                <tr class="cart-item">
                                                    <td class="product-name">
                                                        <?= $row['prod_name'] ?>&nbsp;
                                                        <strong>×&nbsp;<?= $row['cart_quantity'] ?></strong>
                                                    </td>
                                                    <td class="product-total">
                                                        <span> ₱<?= number_format($item_total, 2) ?> </span>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                        </tbody>
                                    <?php     } else {
                                        echo '<meta http-equiv="refresh" content="0;url=login.php">';
                                        exit;
                                    }

                                    ?>
                                    <tfoot>
                                        <tr class="cart-subtotal">
                                            <th>Subtotal</th>
                                            <td>
                                                <span> ₱<?php echo number_format($subtotal, 2); ?> </span>
                                            </td>
                                        </tr>

                                        <tr class="cart-shipping">
                                            <th>Delivery fee</th>
                                            <td data-title="Shipping">
                                                <form action="#">
                                                    <ul class="shipping-methods">
                                                        <li class="single-form">
                                                            <label for="flat-rate" class="single-form__label radio-label">
                                                                <strong class="price">
                                                                    ₱<?php echo number_format($delivery_fee, 2); ?>
                                                                </strong>
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </form>
                                            </td>
                                        </tr>

                                        <tr class="order-total">
                                            <th>Total</th>
                                            <td>
                                                <strong>
                                                    <span>
                                                        ₱<?php echo number_format($subtotal + $delivery_fee, 2); ?>
                                                    </span>
                                                </strong>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="checkout-details__payment-method">
                                    <div class="accordion" id="payment-method">

                                            <div class="accordion-item">
                                                <div class="single-form" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                                    <input type="radio" name="payment_method" id="bank-transfer" value="Direct bank transfer" required/>
                                                    <label for="bank-transfer" class="single-form__label radio-label">
                                                        <span></span>
                                                        Direct bank
                                                        transfer
                                                    </label>
                                                </div>
                                                <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#payment-method">
                                                    <div class="payment-method-body">
                                                        <p></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <div class="single-form collapsed" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                                    <input type="radio" name="payment_method" id="check-payment" value="G-Cash" required/>
                                                    <label for="check-payment" class="single-form__label radio-label">
                                                        <span></span>
                                                        G-Cash
                                                    </label>
                                                </div>
                                                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#payment-method">
                                                    <div class="payment-method-body">
                                                        <p></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <div class="single-form collapsed" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                                    <input type="radio" name="payment_method" id="cash-on-delivery" value="Cash on Delivery" required/>
                                                    <label for="cash-on-delivery" class="single-form__label radio-label">
                                                        <span></span>
                                                        Cash On Delivery
                                                    </label>
                                                </div>
                                                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#payment-method">
                                                    <div class="payment-method-body">
                                                        <p></p>
                                                    </div>
                                                </div>
                                            </div>

                                    </div>
                                </div>

                                <div class="checkout-details__privacy-policy">
                                    <p>
                                        Your personal data will be used
                                        to process your order, support
                                        your experience throughout this
                                        website, and for other purposes
                                        described in our privacy policy.
                                    </p>
                                </div>

                                <div class="checkout-details__btn">
                                    <button class="btn">
                                        Place Order
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Checkout Details End -->
                    </div>
                </div>
            </form>

        </div>
        <!-- Checkout End -->
    </div>
</div>
<!-- Checkout End -->
<?php include('layouts/footer.php') ?>
<?php include('layouts/header.php') ?>
<?php
// Prepare and execute the SQL statement
$sql = "SELECT COUNT(*) AS count FROM carts WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Check if the order ID exists
if ($row['count'] == 0) { ?>
    <!-- Breadcrumb Start -->
    <div class="breadcrumb-section">
        <div class="container-fluid custom-container">
            <div class="breadcrumb-wrapper text-center">
                <h2 class="breadcrumb-wrapper__title">Cart Empty</h2>
                <ul class="breadcrumb-wrapper__items justify-content-center">
                    <li><a href="/">Home</a></li>
                    <li><span>Cart Empty</span></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Cart Start -->
    <div class="cart-section section-padding-2">
        <div class="container-fluid custom-container">
            <!-- Cart Empty Start -->
            <div class="cart-empty text-center">
                <img src="assets/images/cart-empty.svg" alt="Cart Empty" width="230" height="230" />
                <p>Your cart is currently empty.</p>

                <a href="flowers.php" class="cart-empty__btn btn">Return to shop</a>
            </div>
            <!-- Cart Empty End -->
        </div>
    </div>
    <!-- Cart End -->
<?php } else { ?>
    <!-- Breadcrumb Start -->
    <div class="breadcrumb-section">
        <div class="container-fluid custom-container">
            <div class="breadcrumb-wrapper text-center">
                <h2 class="breadcrumb-wrapper__title">Cart</h2>
                <ul class="breadcrumb-wrapper__items justify-content-center">
                    <li><a href="/">Home</a></li>
                    <li><span>Cart</span></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->
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

        <!-- Cart Start -->
        <div class="cart-section section-padding-2">
            <div class="container-fluid custom-container">
                <!-- Cart Wrapper Start-->
                <div class="cart-wrapper">
                    <!-- Cart Form Start-->
                    <div class="cart-form">

                        <!-- Cart Table Start-->
                        <div class="cart-table table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="cart-product-remove">
                                            &nbsp;
                                        </th>
                                        <th class="cart-product-thumbnail">
                                            &nbsp;
                                        </th>
                                        <th class="cart-product-name">
                                            Product
                                        </th>
                                        <th class="cart-product-price text-center">
                                            Price
                                        </th>
                                        <th class="cart-product-quantity text-center">
                                            Quantity
                                        </th>
                                        <th class="cart-product-subtotal text-center">
                                            Subtotal
                                        </th>
                                    </tr>
                                </thead>
                                <form action="" method="post">
                                    <tbody>
                                        <?php while ($row = $cart_result->fetch_assoc()) {
                                            $item_total = $row['price'] * $row['cart_quantity'];
                                            $subtotal += $item_total;
                                        ?>
                                            <tr class="cart-item">
                                                <td class="cart-product-remove">
                                                    <a href="removecart.php?cart_id=<?= $row['cart_id'] ?>" class="remove">×</a>
                                                </td>

                                                <td class="cart-product-thumbnail">
                                                    <a href="flower-info.php?prod_no=<?= $row['prod_no'] ?>">
                                                        <img src="prodimg/<?= $row['prod_img'] ?>" alt="Product" width="70" height="89" />
                                                    </a>
                                                </td>

                                                <td class="cart-product-name">
                                                    <a href="flower-info.php?prod_no=<?= $row['prod_no'] ?>">
                                                        <?= $row['prod_name'] ?>
                                                    </a>
                                                </td>

                                                <td class="cart-product-price text-md-center" data-title="Price">
                                                    <span class="price-amount">
                                                        <ins>₱<?php echo number_format($row['price'], 2); ?></ins>
                                                    </span>
                                                </td>

                                                <td class="cart-product-quantity text-md-center" data-title="Quantity">
                                                    <div class="cart-table__quantity product-quantity">
                                                        <button type="button" class="decrease" aria-label="delete">
                                                            <i class="lastudioicon-i-delete-2"></i>
                                                        </button>
                                                        <input class="quantity-input" type="text" name="quantity[<?php echo $row['cart_id']; ?>]" value="<?php echo $row['cart_quantity']; ?>" min="1" />
                                                        <button type="button" class="increase" aria-label="add">
                                                            <i class="lastudioicon-i-add-2"></i>
                                                        </button>
                                                    </div>
                                                </td>

                                                <td class="cart-product-subtotal text-md-center" data-title="Subtotal">
                                                    <span class="price-amount">
                                                        ₱<?php echo number_format($item_total, 2); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php } ?>

                                        <tr class="">
                                            <td colspan="6" class="actions">
                                                <button class="cart-update-btn" type="submit" name="update_cart">
                                                    Update cart
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </form>

                            </table>
                        </div>
                        <!-- Cart Table Start-->
                    </div>
                    <!-- Cart Form End-->

                    <!-- Cart Collaterals Start-->
                    <div class="cart-collaterals">
                        <!-- Cart Totals Start-->
                        <div class="cart-totals">
                            <h3 class="cart-totals__title">Cart totals</h3>

                            <div class="cart-totals__table table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th>Subtotal</th>
                                            <td>
                                                <span>₱ <?php echo number_format($subtotal, 2); ?></span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Delivery Fee</th>
                                            <td>
                                                <ul class="shipping-methods">
                                                    <li class="single-form">
                                                        <label for="flat-rate" class="single-form__label radio-label">
                                                            <strong class="price">₱ <?php echo number_format($delivery_fee, 2); ?></strong>
                                                        </label>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>

                                        <tr class="order-total">
                                            <th>Total</th>
                                            <td>
                                                <strong>₱ <?php echo number_format($subtotal + $delivery_fee, 2); ?></strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="cart-totals__checkout">
                                <a href="checkout.php">Proceed to checkout</a>
                            </div>
                        </div>
                        <!-- Cart Totals End-->
                    </div>
                    <!-- Cart Collaterals End-->
                </div>
                <!-- Cart Wrapper End -->
            </div>
        </div>
        <!-- Cart End -->
    <?php
        if (isset($_POST['update_cart'])) {
            foreach ($_POST['quantity'] as $cart_id => $quantity) {

                $update_stmt = $conn->prepare("UPDATE carts SET quantity = ? WHERE cart_id = ? AND userid = ?");
                $update_stmt->bind_param("iii", $quantity, $cart_id, $userid);
                $update_stmt->execute();
                $update_stmt->close();
            }
            $_SESSION['alert'] = "<script>
                                        Toast.fire({
                                            icon: 'success',
                                            title: 'Successfully Updated Cart',
                                        });
                                    </script>";
            echo '<meta http-equiv="refresh" content="0;url=viewcart.php">';
            exit;
        }
    } else {
        echo '<meta http-equiv="refresh" content="0;url=login.php">';
        exit;
    } ?>
<?php }
?>

<?php include('layouts/footer.php') ?>
    <!-- Cart Sidebar Start -->
    <!-- Cart Offcanvas Start -->
    <div class="offcanvas offcanvas-end cart-offcanvas" id="cartSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">My Cart</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="remove">
                <i class="lastudioicon-e-remove"></i>
            </button>
        </div>

        <?php
        if (isset($_SESSION['userid'])) {
            $userId = $_SESSION['userid'];
            // Assuming you have a connection to the database in $conn
            $query = "SELECT carts.*, carts.quantity AS cart_quant, carts.color AS cart_color, products.*, carts.quantity AS cart_quantity
              FROM carts 
              JOIN products ON carts.prod_no = products.prod_no 
              WHERE carts.userid = ?";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {

        ?>
                <?php
                $totalPrice = 0;
                while ($row = $result->fetch_assoc()) {
                    $totalPrice += $row['cart_quantity'] * $row['price'];
                }
                ?>
                <div class="offcanvas-body">
                    <ul class="offcanvas-cart-list">
                        <li>
                            <?php
                            mysqli_data_seek($result, 0);
                            while ($row = $result->fetch_assoc()) : ?>
                                <!-- Offcanvas Cart Item Start -->
                                <div class="offcanvas-cart-item">
                                    <div class="offcanvas-cart-item__thumbnail">
                                        <a href="#">
                                            <img src="prodimg/<?=$row['prod_img']?>" width="70" height="84" alt="product" />
                                        </a>
                                    </div>
                                    <div class="offcanvas-cart-item__content">
                                        <h4 class="offcanvas-cart-item__title">
                                            <a href="#"><?=$row['prod_name']?></a>
                                        </h4>
                                        <span class="offcanvas-cart-item__quantity">
                                        <?=$row['cart_quant']?> × ₱<?=$row['price']?>
                                        </span>
                                        <small><?=$row['cart_color']?></small>
                                    </div>
                                    <a class="offcanvas-cart-item__remove" href="removecart.php?cart_id=<?= $row['cart_id'] ?>" aria-label="remove">
                                        <i class="lastudioicon-e-remove"></i>
                                    </a>
                                </div>
                                <!-- Offcanvas Cart Item End -->
                            <?php endwhile; ?>


                        </li>

                    </ul>
                </div>
                <div class="offcanvas-footer">

                    <!-- Cart Totals Table Start-->
                    <div class="cart-totals-table">
                        <table class="table">
                            <tbody>
                                <tr class="cart-subtotal">
                                    <th>Subtotal</th>
                                    <td>
                                        <span>₱<?= number_format($totalPrice, 2) ?></span>
                                    </td>
                                </tr>

                                <tr class="cart-shipping-totals">
                                    <th>Delivery Fee</th>
                                    <td>
                                        <span>₱<?=number_format($delivery_fee, 2)?></span>
                                    </td>
                                </tr>

                                <tr class="order-total">
                                    <th>Total</th>
                                    <td data-title="Total">
                                        <span>₱<?= number_format($totalPrice + $delivery_fee, 2) ?></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Cart Totals Table End-->

                    <!-- Cart Buttons End-->
                    <div class="cart-buttons">
                        <a href="checkout.php" class="cart-buttons__btn-1 btn">Checkout</a>
                        <a href="viewcart.php" class="cart-buttons__btn-2 btn">View Cart</a>
                    </div>
                    <!-- Cart Buttons End-->
                </div>
            <?php
            } else { ?>
                <p class="text-center">No List</p>
        <?php    }
        }
        ?>

    </div>
    <!-- Cart Offcanvas End -->

    <!-- Cart Sidebar End -->
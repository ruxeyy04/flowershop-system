<?php
include('config.php');

if (isset($_SESSION['userid'])) {
    if (isset($_GET['prod_no']) || isset($_POST['prod_no'])) {
        $prod_no = $_GET['prod_no'] ?? $_POST['prod_no'];
        $user_id = $_SESSION['userid'];
        $quantity = $_GET['quantity'] ?? $_POST['quantity'] ?? 1;





        $check_stmt = $conn->prepare("SELECT status FROM products WHERE prod_no = ?");
        $check_stmt->bind_param("s", $prod_no);
        $check_stmt->execute();
        $check_stmt->bind_result($status);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($status === "Available") {
            // Check if the product is already in the cart
            $cart_check_stmt = $conn->prepare("SELECT quantity FROM carts WHERE prod_no = ? AND userid = ?");
            $cart_check_stmt->bind_param("ii", $prod_no, $user_id);
            $cart_check_stmt->execute();
            $cart_check_stmt->store_result();

            if ($cart_check_stmt->num_rows > 0) {
                // Product exists in the cart, update the quantity
                $cart_check_stmt->bind_result($current_quantity);
                $cart_check_stmt->fetch();
                $new_quantity = $current_quantity + $quantity;

                $update_stmt = $conn->prepare("UPDATE carts SET quantity = ?, updated_at = '$timestamp' WHERE prod_no = ? AND userid = ?");
                $update_stmt->bind_param("iii", $new_quantity, $prod_no, $user_id);

                if ($update_stmt->execute()) {
                    $_SESSION['alert'] = "<script>
                                        Toast.fire({
                                            icon: 'success',
                                            title: 'The product quantity($new_quantity) has been updated in the cart',
                                        });
                                    </script>";
                } else {
                    $_SESSION['alert'] = "<script>
                                        Toast.fire({
                                            icon: 'error',
                                            title: '{$update_stmt->error}',
                                        });
                                    </script>";
                }

                $update_stmt->close();
            } else {
                // Product does not exist in the cart, insert a new entry
                $insert_stmt = $conn->prepare("INSERT INTO carts (prod_no, userid, quantity, updated_at, created_at) VALUES (?, ?, ?, '$timestamp', '$timestamp')");
                $insert_stmt->bind_param("iii", $prod_no, $user_id, $quantity);

                if ($insert_stmt->execute()) {
                    $_SESSION['alert'] = "<script>
                                        Toast.fire({
                                            icon: 'success',
                                            title: 'Successfully added to cart',
                                        });
                                    </script>";
                } else {
                    $_SESSION['alert'] = "<script>
                                        Toast.fire({
                                            icon: 'error',
                                            title: '{$insert_stmt->error}',
                                        });
                                    </script>";
                }

                $insert_stmt->close();
            }

            $cart_check_stmt->close();
            $previous_url = $_SERVER['HTTP_REFERER'] ?? 'products.php';
            header("Location: $previous_url");
        } else {
            // Product is not available, redirect back
            $_SESSION['alert'] = "<script>
                                  Toast.fire({
                                      icon: 'error',
                                      title: 'Product is not available',
                                  });
                              </script>";
            $previous_url = $_SERVER['HTTP_REFERER'] ?? 'products.php';
            header("Location: $previous_url");
        }
    } else {
        $previous_url = $_SERVER['HTTP_REFERER'] ?? 'products.php';
        header("Location: $previous_url");
    }
} else {
    header('Location: login-register.php');
}

$conn->close();

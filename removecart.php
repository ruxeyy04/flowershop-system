<?php
include('config.php');

if (isset($_SESSION['userid'])) {
  if (isset($_GET['cart_id']) || isset($_POST['cart_id'])) {
    $cart_id = $_GET['cart_id'] ?? $_POST['cart_id'];
    $user_id = $_SESSION['userid'];

    $stmt = $conn->prepare("DELETE FROM carts WHERE cart_id = ? AND userid = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);

    if ($stmt->execute()) {
      $_SESSION['alert'] = "<script>
                              Toast.fire({
                                  icon: 'success',
                                  title: 'Successfully Removed to Cart',
                              });
                          </script>";
    } else {
      $_SESSION['alert'] = "<script>
                              Toast.fire({
                                  icon: 'error',
                                  title: $stmt->error,
                              });
                          </script>";
    }
    $stmt->close();

    $previous_url = $_SERVER['HTTP_REFERER'] ?? 'fries.php';
    header("Location: $previous_url");
  } else {
    header('Location: fries.php');
  }
} else {
  header('Location: login-register.php');
}

$conn->close();
?>

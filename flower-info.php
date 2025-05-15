<?php include('layouts/header.php') ?>
<!-- Breadcrumbs Start -->
<?php

$prod_no = isset($_GET['prod_no']) ? (int)$_GET['prod_no'] : null;

if (!$prod_no) {
    echo '<meta http-equiv="refresh" content="0;url=flowers.php">';
    exit;
}

// Fetch product details
$product_query = "SELECT a.*, b.category_name FROM products a INNER JOIN category b ON a.category_id=b.category_id WHERE prod_no = ?";
$stmt = $conn->prepare($product_query);
$stmt->bind_param("i", $prod_no);
$stmt->execute();
$product_result = $stmt->get_result();
$flower = $product_result->fetch_assoc();


if (!$flower) {
    echo '<meta http-equiv="refresh" content="0;url=flowers.php">';
    exit;
}

$in_cart = false;
if ($userid) {
    $cart_check_query = "SELECT * FROM carts WHERE prod_no = ? AND userid = ?";
    $stmt = $conn->prepare($cart_check_query);
    $stmt->bind_param("ii", $prod_no, $userid);
    $stmt->execute();
    $cart_check_result = $stmt->get_result();
    $cart_row = $cart_check_result->fetch_assoc();
    $in_cart = $cart_check_result->num_rows > 0;
    $cart_id = $in_cart ? $cart_row['cart_id'] : null;
}

?>
<div class="single-breadcrumbs">
    <div class="container-fluid custom-container">
        <ul class="single-breadcrumbs-list">
            <li><a href="/">Home</a></li>
            <li><a href="flowers.php">Flower</a></li>
            <li><span><?= $flower['prod_name'] ?></span></li>
        </ul>
    </div>
</div>
<!-- Breadcrumbs End -->

<!-- Product Single Start -->
<div class="product-single-section section-padding-2">
    <div class="container-fluid custom-container">
        <!-- Product Single Wrapper Start -->
        <div class="product-single-wrapper">
            <div class="product-single-col-1">
                <!-- Product Single image Start -->
                <div class="product-single-image">
                    <div class="product-single-slide navigation-arrows-style-1">
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                <div class="product-single-slide-item swiper-slide">
                                    <img src="prodimg/<?= $flower['prod_img'] ?>" alt="Product single" width="694" height="728" />
                                </div>
                            </div>
                            <div class="product-single-zoom">
                                <div class="zoom">
                                    <a class="product-glightbox" href="prodimg/<?= $flower['prod_img'] ?>" aria-label="zoom image"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-single-thumb">
                        <div class="swiper">
                        </div>
                    </div>
                </div>
                <!-- Product Single image End -->
            </div>

            <div class="product-single-col-2">
                <!-- Product Single content Start -->
                <div class="product-single-content">
                    <h2 class="product-single-content__title">
                        <?= $flower['prod_name'] ?>
                    </h2>
                    <div class="product-single-content__price-stock">
                        <div class="product-single-content__price">
                            <ins>₱<?= $flower['price'] ?></ins>
                        </div>
                        <div class="product-single-content__stock">
                            <span class="stock-icon">
                                <i class="dlicon ui-1_check-circle-08"></i>
                            </span>
                            <span class="stock-text">
                                <div class="text-<?= $flower['status'] === 'Available' ? 'success' : 'danger'; ?>">
                                    <span>
                                        <i class="lastudioicon-<?= $flower['status'] === 'Available' ? 'check' : 'times'; ?>"></i>
                                        <?= $flower['status'] === 'Available' ? 'Available' : 'Unavailable'; ?>
                                    </span>
                                </div>
                            </span>
                        </div>
                    </div>
                    <div class="product-single-content__short-description">
                        <p>
                            <?= $flower['description'] ?>
                        </p>
                    </div>
                    <div class="product-single-content__add-to-cart-wrapper">
                        <?php
                        if ($in_cart) { ?>
                            <form action="removecart.php" method="get">
                                <div class="product-single-content__quantity-add-to-cart">
                                    <button type="submit" class="product-single-content__add-to-cart btn" name="cart_id" value="<?= $cart_id ?>">
                                        Remove to Cart
                                    </button>
                                </div>
                            </form>
                        <?php } else { ?>
                            <form action="addcart.php" method="get">
                                <div class="product-single-content__quantity-add-to-cart">
                                    <div class="product-single-content__quantity product-quantity">
                                        <button type="button" class="decrease" aria-label="delete">
                                            <i class="lastudioicon-i-delete-2"></i>
                                        </button>
                                        <input class="quantity-input" type="text" name="quantity" value="1" readonly />
                                        <button type="button" class="increase" aria-label="add">
                                            <i class="lastudioicon-i-add-2"></i>
                                        </button>
                                    </div>
                                    <button type="submit" class="product-single-content__add-to-cart btn" name="prod_no" value="<?= $flower['prod_no'] ?>">
                                        Add to Cart
                                    </button>
                                </div>
                            </form>
                        <?php    }
                        ?>


                    </div>
                    <div class="product-single-content__meta">
                        <div class="product-single-content__meta--item">
                            <div class="label">Categories:</div>
                            <div class="content">
                                <a href="#"><?= $flower['category_name'] ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Product Single content End -->
            </div>
        </div>
        <!-- Product Single Wrapper End -->
    </div>
</div>
<!-- Product Single End -->

<!-- Product Single Tabs Start -->
<div class="product-single-tabs-section section-padding-2">
    <div class="container-fluid custom-container">
        <!-- Product Single Tabs Start -->
        <div class="product-single-tabs">
            <ul class="nav justify-content-center">
                <li>
                    <button class="active" data-bs-toggle="pill" data-bs-target="#description" type="button">
                        Description
                    </button>
                </li>
                <li>
                    <button data-bs-toggle="pill" data-bs-target="#additionalInformation " type="button">
                        Additional information
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="description">
                    <?= $flower['description'] ?>
                </div>
                <div class="tab-pane fade" id="additionalInformation">
                    <!-- Product Single Table Start -->
                    <div class="product-single-table">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <tbody>
                                <tbody>
                                    <tr>
                                        <th>Category</th>
                                        <td>
                                            <p><?= $flower['category_name'] ?></p>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Product Single Table End -->
                </div>
            </div>
        </div>
        <!-- Product Single Tabs End -->
    </div>
</div>

<!-- Related Product Start -->
<div class="related-product-section section-padding-2">
    <div class="container-fluid custom-container">
        <!-- Related Title Start -->
        <div class="related-title text-center">
            <h2 class="related-title__title">Related Products</h2>
        </div>
        <!-- Related Title End -->

        <!-- Related Product Start -->
        <div class="related-product-active swiper-dot-style-1">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php
                    $sql = "SELECT a.*, b.category_name FROM products a INNER JOIN category b ON a.category_id=b.category_id ORDER BY RAND() LIMIT 8";
                    $result = mysqli_query($conn, $sql);
                    $products = [];

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $products[] = $row;
                        }
                    }
                    if (!empty($products)) {
                        foreach ($products as $row) {
                            $modal_id = 'flowers_' . $row['prod_no'];
                            $prod_no = $row['prod_no'];
                            $is_in_cart = false;

                            if (isset($_SESSION['userid'])) {
                                $userid = $_SESSION['userid'];
                                $cart_check_sql = "SELECT * FROM carts WHERE prod_no = $prod_no AND userid = $userid";
                                $cart_check_result = mysqli_query($conn, $cart_check_sql);
                                $is_in_cart = mysqli_num_rows($cart_check_result) > 0;
                                if ($is_in_cart) {
                                    $cart_row = mysqli_fetch_assoc($cart_check_result);
                                    $cart_id = $cart_row['cart_id'];
                                }
                            }
                    ?>
                            <div class="swiper-slide">
                                <div class="single-product js-scroll ShortFadeInUp scrolled">
                                    <div class="single-product__thumbnail">
                                        <div class="single-product__thumbnail--holder">
                                            <a href="flower-info.php?prod_no=<?= $row['prod_no'] ?>">
                                                <img src="prodimg/<?= $row['prod_img'] ?>" alt="Product" width="392" height="400" loading="lazy" />
                                            </a>
                                        </div>
                                        <div class="single-product__thumbnail--meta-2">
                                            <?php if ($is_in_cart) { ?>
                                                <a href="removecart.php?cart_id=<?= $cart_id ?>" data-bs-tooltip="tooltip" data-bs-placement="top" data-bs-title="Remove to cart" data-bs-custom-class="p-meta-tooltip" aria-label="Remove to cart">
                                                    <i class="lastudioicon-cart-return"></i>
                                                </a>
                                            <?php } else { ?>
                                                <a href="addcart.php?prod_no=<?= $row['prod_no'] ?>" data-bs-tooltip="tooltip" data-bs-placement="top" data-bs-title="Add to cart" data-bs-custom-class="p-meta-tooltip" aria-label="Add to cart">
                                                    <i class="lastudioicon-shopping-cart-3"></i>
                                                </a>
                                            <?php } ?>

                                            <button data-bs-tooltip="tooltip" data-bs-placement="top" data-bs-title="Quickview" data-bs-custom-class="p-meta-tooltip" data-bs-toggle="modal" data-bs-target="#<?= $modal_id ?>" aria-label="quickView">
                                                <i class="lastudioicon-search-zoom-in"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="single-product__info">
                                        <div class="single-product__info--tags">
                                            <a href="#"><?= $row['category_name'] ?></a>
                                        </div>
                                        <h3 class="single-product__info--title">
                                            <a href="flower-info.php?prod_no=<?= $row['prod_no'] ?>">
                                                <?= $row['prod_name'] ?>
                                            </a>
                                        </h3>
                                        <div class="single-product__info--price">
                                            <ins>₱<?= $row['price'] ?></ins>
                                        </div>
                                    </div>
                                </div>

                            </div>

                    <?php
                        }
                    } else {
                        $_SESSION['alert'] = "<script>
                                Toast.fire({
                                    icon: 'info',
                                    title: 'No Data',
                                });
                            </script>";
                    }
                    ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <!-- Related Product End -->
    </div>
</div>
<?php
if (!empty($products)) {
    foreach ($products as $row) {
        $modal_id = 'flowers_' . $row['prod_no'];
        $prod_no = $row['prod_no'];
        $is_in_cart = false;

        if (isset($_SESSION['userid'])) {
            $userid = $_SESSION['userid'];
            $cart_check_sql = "SELECT * FROM carts WHERE prod_no = $prod_no AND userid = $userid";
            $cart_check_result = mysqli_query($conn, $cart_check_sql);
            $is_in_cart = mysqli_num_rows($cart_check_result) > 0;
            if ($is_in_cart) {
                $cart_row = mysqli_fetch_assoc($cart_check_result);
                $cart_id = $cart_row['cart_id'];
            }
        }
?>
        <!-- Quick View Start -->
        <!-- Modal Start -->
        <div class="modal quickview-modal fade" id="<?= $modal_id ?>">
            <div class="modal-dialog modal-dialog-centered">
                <!-- Modal Content Start -->
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="remove">
                        <i class="lastudioicon-e-remove"></i>
                    </button>
                    <div class="modal-body">
                        <div class="row g-0">
                            <div class="col-md-6">
                                <!-- Product Single image Start -->
                                <div class="product-single-image">
                                    <div class="product-single-slide-item swiper-slide">
                                        <img src="prodimg/<?= $row['prod_img'] ?>" alt="Flower" width="742" height="778" loading="lazy" />
                                    </div>
                                </div>
                                <!-- Product Single image End -->
                            </div>
                            <div class="col-md-6">
                                <!-- Product Single Content Start -->
                                <div class="product-single-content quick-view-product-content">
                                    <h2 class="product-single-content__title">
                                        <?= $row['prod_name'] ?>
                                    </h2>
                                    <div class="product-single-content__price-stock">
                                        <div class="product-single-content__price">
                                            <ins>₱<?= $row['price'] ?></ins>
                                        </div>
                                        <div class="product-single-content__stock">
                                            <span class="stock-icon" aria-label="check-circle">
                                                <i class="dlicon ui-1_check-circle-08"></i>
                                            </span>
                                            <span><i class="fa fa-check <?= $row['status'] !== 'Available' ? 'text-danger' : '' ?>"></i><?= $row['status'] ?></span>
                                        </div>
                                    </div>
                                    <div class="product-single-content__short-description">
                                        <p>
                                            <?= $row['description'] ?>
                                        </p>
                                    </div>
                                    <div class="product-single-content__add-to-cart-wrapper">
                                        <?php if ($is_in_cart) { ?>
                                            <form action="removecart.php" method="get">
                                                <div class="product-single-content__quantity-add-to-cart">
                                                    <button class="product-single-content__add-to-cart btn" type="submit" name="cart_id" value="<?= $cart_id ?>">
                                                        Remove to Cart
                                                    </button>
                                                </div>
                                            </form>
                                        <?php } else { ?>

                                            <form action="addcart.php" method="get">
                                                <div class="product-single-content__quantity-add-to-cart">
                                                    <div class="product-single-content__quantity product-quantity">
                                                        <button type="button" class="decrease" aria-label="delete">
                                                            <i class="lastudioicon-i-delete-2"></i>
                                                        </button>
                                                        <input class="quantity-input" type="text" value="1" min="1" name="quantity" readonly />
                                                        <button type="button" class="increase" aria-label="add">
                                                            <i class="lastudioicon-i-add-2"></i>
                                                        </button>
                                                    </div>
                                                    <button class="product-single-content__add-to-cart btn" type="submit" name="prod_no" value="<?= $row['prod_no'] ?>">
                                                        Add to Cart
                                                    </button>
                                                </div>
                                            </form>
                                        <?php } ?>


                                    </div>
                                    <div class="product-single-content__meta">
                                        <div class="product-single-content__meta--item">
                                            <div class="label">Categories:</div>
                                            <div class="content">
                                                <a href="#"><?= $row['category_name'] ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Product Single Content End -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Content End -->
            </div>
        </div>
        <!-- Modal End -->

        <!-- Quick View End -->
<?php
    }
}
?>
<!-- Related Product End -->
<?php include('layouts/footer.php') ?>
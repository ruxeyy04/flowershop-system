<?php include('layouts/header.php') ?>
<!-- Slider Start -->
<div class="slider-section slider-active navigation-arrows-style-1">
    <div class="swiper">
        <div class="swiper-wrapper">
            <!-- Slider Item Start -->
            <div class="slider-item home-1-slider-style-1 swiper-slide d-md-flex align-items-center home-1-slider-animation" style="
                                background-image: url(assets/images/slider/slider-1.jpg);
                            ">
                <div class="container-fluid custom-container">
                    <div class="home-1-slider-content-style-1 text-center">
                        <h3 class="home-1-slider-content-style-1__sub-title">
                            Welcome to
                        </h3>
                        <h2 class="home-1-slider-content-style-1__title">
                            Blossom Box
                        </h2>

                        <ul class="home-1-slider-content-style-1__btns justify-content-center">
                            <li class="button-01">
                                <a class="home-1-slider-content-style-1__btn" href="flowers.php">
                                    Browse
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- Slider Content End -->
                </div>
            </div>
            <!-- Slider Item End -->
            <!-- Slider Item Start -->
            <div class="slider-item home-1-slider-style-1 swiper-slide d-md-flex align-items-center home-1-slider-animation" style="
                                background-image: url(assets/images/slider/slider-2.jpg);
                            ">
                <div class="container-fluid custom-container">
                    <!-- Slider Content Start -->
                    <div class="home-1-slider-content-style-1 text-center">
                        <h3 class="home-1-slider-content-style-1__sub-title">
                            Blossom Box
                        </h3>
                        <h2 class="home-1-slider-content-style-1__title">
                            Light up your moments
                        </h2>

                        <ul class="home-1-slider-content-style-1__btns justify-content-center">
                            <li class="button-01">
                                <a class="home-1-slider-content-style-1__btn" href="flowers.php">
                                    Browse
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- Slider Content End -->
                </div>
            </div>
            <!-- Slider Item End -->
            <!-- Slider Item Start -->
            <div class="slider-item home-1-slider-style-1 swiper-slide d-md-flex align-items-center home-1-slider-animation" style="
                                background-image: url(assets/images/slider/slider-3.jpg);
                            ">
                <div class="container-fluid custom-container">
                    <!-- Slider Content Start -->
                    <div class="home-1-slider-content-style-1 text-center">
                        <h3 class="home-1-slider-content-style-1__sub-title">
                            Blossom Box
                        </h3>
                        <h2 class="home-1-slider-content-style-1__title">
                            Bring the light in your life
                        </h2>

                        <ul class="home-1-slider-content-style-1__btns justify-content-center">
                            <li class="button-01">
                                <a class="home-1-slider-content-style-1__btn" href="flowers.php">
                                    Browse
                                </a>
                            </li>
                        </ul>

                    </div>
                    <!-- Slider Content End -->
                </div>
            </div>
            <!-- Slider Item End -->
        </div>

        <div class="swiper-button-prev">
            <i class="lastudioicon-arrow-left"></i>
        </div>
        <div class="swiper-button-next">
            <i class="lastudioicon-arrow-right"></i>
        </div>
    </div>
</div>
<!-- Slider End -->

<!-- Product Start -->
<div class="product-section section-padding">
    <div class="container-fluid home-container">
        <!-- Section Title Start -->
        <div class="section-title text-center js-scroll ShortFadeInUp scrolled">
            <h2 class="section-title__title">Our Products</h2>
            <div class="section-title__shape">
                <img src="assets/images/section-shape-1.svg" alt="shape" width="129" height="136" loading="lazy" />
            </div>
        </div>
        <!-- Section Title End -->

        <!-- Product wrapper Start -->
        <div class="product-wrapper">
            <div class="row g-xxl-4">
                <?php
                $sql = "SELECT a.*, b.category_name FROM products a INNER JOIN category b ON a.category_id=b.category_id ORDER BY RAND() LIMIT 8";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $modal_id = 'flowers_' . $row['prod_no'];
                        $prod_no = $row['prod_no'];
                        if (isset($_SESSION['userid'])) {
                            $cart_check_sql = "SELECT * FROM carts WHERE prod_no = $prod_no AND userid = $userid";
                            $cart_check_result = mysqli_query($conn, $cart_check_sql);
                            $is_in_cart = mysqli_num_rows($cart_check_result) > 0;
                            if ($is_in_cart) {
                                $cart_row = mysqli_fetch_assoc($cart_check_result);
                                $cart_id = $cart_row['cart_id'];
                            }
                        } else {
                            $is_in_cart = false;
                        }
                ?>
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <!-- Single product Start -->
                            <div class="single-product js-scroll ShortFadeInUp scrolled">
                                <div class="single-product__thumbnail">
                                    <div class="single-product__thumbnail--holder">
                                        <a href="flower-info.php?prod_no=<?= $row['prod_no'] ?>">
                                            <img src="prodimg/<?= $row['prod_img'] ?>" alt="Flower" width="392" height="400" loading="lazy" />
                                        </a>
                                    </div>
                                    <div class="single-product__thumbnail--meta-2">
                                        <a href="addcart.php?prod_no=<?= $row['prod_no'] ?>" data-bs-tooltip="tooltip" data-bs-placement="top" data-bs-title="Add to cart" data-bs-custom-class="p-meta-tooltip" aria-label="Add to cart">
                                            <i class="lastudioicon-shopping-cart-3"></i>
                                        </a>
                                        <!-- <?php if ($is_in_cart) { ?>
                                            <a href="removecart.php?cart_id=<?= $cart_id ?>" data-bs-tooltip="tooltip" data-bs-placement="top" data-bs-title="Remove to cart" data-bs-custom-class="p-meta-tooltip" aria-label="Remove to cart">
                                                <i class="lastudioicon-cart-return"></i>
                                            </a>
                                        <?php } else { ?>
                                            
                                        <?php } ?> -->

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
                            <!-- Single product End -->
                        </div>
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
                                                                <div class="sidebar-widget-item border-0 p-0 mb-4">
                                                                 
                                                                </div>
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
        </div>
        <!-- Product wrapper End -->

        <!-- Product More Start -->
        <div class="text-center js-scroll ShortFadeInUp scrolled">
            <a class="view-more-btn" href="flowers.php">
                VIEW MORE PRODUCTS
            </a>
        </div>
        <!-- Product More End -->
    </div>
</div>
<!-- Product End -->
<!-- Instagram Start -->
<div class="instagram-section section-padding overflow-hidden">
    <div class="container-fluid home-container">
        <!-- Section Title Start -->
        <div class="section-title text-center js-scroll ShortFadeInUp scrolled">
            <h2 class="section-title__title">Blossom Box Gallery</h2>
            <div class="section-title__shape">
                <img src="assets/images/section-shape-1.svg" alt="shape" width="129" height="136" loading="lazy" />
            </div>
        </div>
        <!-- Section Title End -->

        <!-- Instagram Active Start -->
        <div class="instagram-wrapper instagram-active js-scroll ShortFadeInUp scrolled">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <!-- Instagram Item Start -->
                        <div class="instagram-item">
                            <a href="#">
                                <div class="instagram-item__image">
                                    <img src="https://images.unsplash.com/photo-1562690868-60bbe7293e94?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=360&h=368&q=80" alt="Flower Bouquet" width="360" height="368" loading="lazy" />
                                </div>
                                <div class="instagram-item__icon">
                                    <i class="lastudioicon-b-instagram"></i>
                                </div>
                            </a>
                        </div>
                        <!-- Instagram Item End -->
                    </div>
                    <div class="swiper-slide">
                        <!-- Instagram Item Start -->
                        <div class="instagram-item">
                            <a href="#">
                                <div class="instagram-item__image">
                                    <img src="https://images.unsplash.com/photo-1596438459194-f275f413d6ff?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=360&h=368&q=80" alt="Flower Arrangement" width="360" height="368" loading="lazy" />
                                </div>
                                <div class="instagram-item__icon">
                                    <i class="lastudioicon-b-instagram"></i>
                                </div>
                            </a>
                        </div>
                        <!-- Instagram Item End -->
                    </div>
                    <div class="swiper-slide">
                        <!-- Instagram Item Start -->
                        <div class="instagram-item">
                            <a href="#">
                                <div class="instagram-item__image">
                                    <img src="https://images.unsplash.com/photo-1508610048659-a06b669e3321?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=360&h=368&q=80" alt="Floral Arrangement" width="360" height="368" loading="lazy" />
                                </div>
                                <div class="instagram-item__icon">
                                    <i class="lastudioicon-b-instagram"></i>
                                </div>
                            </a>
                        </div>
                        <!-- Instagram Item End -->
                    </div>
                    <div class="swiper-slide">
                        <!-- Instagram Item Start -->
                        <div class="instagram-item">
                            <a href="#">
                                <div class="instagram-item__image">
                                    <img src="https://bethoughtful.in/cdn/shop/products/IMG_20201104_204139_1024x1024@2x.jpg?v=1612438343" alt="Blossom Box" width="360" height="368" loading="lazy" />
                                </div>
                                <div class="instagram-item__icon">
                                    <i class="lastudioicon-b-instagram"></i>
                                </div>
                            </a>
                        </div>
                        <!-- Instagram Item End -->
                    </div>
                    <div class="swiper-slide">
                        <!-- Instagram Item Start -->
                        <div class="instagram-item">
                            <a href="#">
                                <div class="instagram-item__image">
                                    <img src="https://officialblossombox.com/cdn/shop/files/a754a8cc-1216-4a36-b12b-b1948bfa0afe.jpg?v=1712945155&width=533" alt="Blossom Box" width="360" height="368" loading="lazy" />
                                </div>
                                <div class="instagram-item__icon">
                                    <i class="lastudioicon-b-instagram"></i>
                                </div>
                            </a>
                        </div>
                        <!-- Instagram Item End -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Instagram Active End -->
    </div>
</div>
<!-- Instagram End -->

<!-- our info Start -->
<div class="our-info-section section-padding">
    <div class="container-fluid custom-container">
        <div class="row gy-4 justify-content-center">
            <div class="col-md-4 col-sm-6">
                <!-- our Info Item Start -->
                <div class="our-info-item text-center js-scroll ShortFadeInUp">
                    <h3 class="our-info-item__title">
                        Opening Hour
                    </h3>
                    <p class="our-info-item__info">
                        10:00 AM - 4:00 PM | Mon - Sat
                    </p>
                </div>
                <!-- our Info Item End -->
            </div>
            <div class="col-md-4 col-sm-6">
                <!-- our Info Item Start -->
                <div class="our-info-item text-center js-scroll ShortFadeInUp">
                    <h3 class="our-info-item__title">
                        Our location
                    </h3>
                    <p class="our-info-item__info">
                        H.T Feliciano St. Aguada
                        Ozamiz City
                    </p>
                </div>
                <!-- our Info Item End -->
            </div>
            <div class="col-md-4 col-sm-6">
                <!-- our Info Item Start -->
                <div class="our-info-item text-center js-scroll ShortFadeInUp">
                    <h3 class="our-info-item__title">Hotline</h3>
                    <p class="our-info-item__info">(088)521-1234</p>
                </div>
                <!-- our Info Item End -->
            </div>
        </div>
    </div>
</div>
<!-- our info End -->

<?php include('layouts/footer.php') ?>
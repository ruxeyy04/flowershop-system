<?php include('layouts/header.php') ?>
<!-- Breadcrumb Start -->
<div class="breadcrumb-section">
    <div class="container-fluid custom-container">
        <div class="breadcrumb-wrapper text-center">
            <h2 class="breadcrumb-wrapper__title">Flowers</h2>
            <ul class="breadcrumb-wrapper__items justify-content-center">
                <li><a href="/">Home</a></li>
                <li><span>Flowers</span></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->
<?php
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 8;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$total_rows_sql = "SELECT COUNT(*) AS total FROM products a INNER JOIN category b ON a.category_id=b.category_id";
$where_added = false;

if (!empty($search)) {
    $total_rows_sql .= " WHERE prod_name LIKE '%$search%'";
    $where_added = true;
}

if (isset($_GET['category']) && is_array($_GET['category']) && !empty($_GET['category'])) {
    $selected_categories = $_GET['category'];

    $escaped_categories = array_map(function ($category) use ($conn) {
        return mysqli_real_escape_string($conn, $category);
    }, $selected_categories);

    $category_list = "'" . implode("','", $escaped_categories) . "'";

    if ($where_added) {
        $total_rows_sql .= " AND b.category_name IN ($category_list)";
    } else {
        $total_rows_sql .= " WHERE b.category_name IN ($category_list)";
        $where_added = true;
    }
}

if (isset($_GET['price'])) {
    $price_range = explode(' - ', $_GET['price']);
    if (count($price_range) === 2) {
        $min_price = filter_var(str_replace('₱', '', $price_range[0]), FILTER_VALIDATE_FLOAT);
        $max_price = filter_var(str_replace('₱', '', $price_range[1]), FILTER_VALIDATE_FLOAT);

        if ($min_price !== false && $max_price !== false && $min_price >= 0 && $max_price >= $min_price) {
            if ($where_added) {
                $total_rows_sql .= " AND ";
            } else {
                $total_rows_sql .= " WHERE ";
                $where_added = true;
            }
            $total_rows_sql .= " price BETWEEN $min_price AND $max_price";
        }
    }
}

$total_items_query = "SELECT COUNT(*) as total FROM products a INNER JOIN category b ON a.category_id=b.category_id";
$where_added = false;

if (!empty($search)) {
    $total_items_query .= " WHERE a.prod_name LIKE '%$search%'";
    $where_added = true;
}

if (isset($_GET['category']) && is_array($_GET['category']) && !empty($_GET['category'])) {
    $selected_categories = $_GET['category'];
    
    $escaped_categories = array_map(function ($category) use ($conn) {
        return mysqli_real_escape_string($conn, $category);
    }, $selected_categories);
    
    $category_list = "'" . implode("','", $escaped_categories) . "'";
    
    if ($where_added) {
        $total_items_query .= " AND b.category_name IN ($category_list)";
    } else {
        $total_items_query .= " WHERE b.category_name IN ($category_list)";
        $where_added = true;
    }
}

if (isset($_GET['price'])) {
    $price_range = explode(' - ', $_GET['price']);
    if (count($price_range) === 2) {
        $min_price = filter_var(str_replace('₱', '', $price_range[0]), FILTER_VALIDATE_FLOAT);
        $max_price = filter_var(str_replace('₱', '', $price_range[1]), FILTER_VALIDATE_FLOAT);
        
        if ($min_price !== false && $max_price !== false && $min_price >= 0 && $max_price >= $min_price) {
            if ($where_added) {
                $total_items_query .= " AND a.price BETWEEN $min_price AND $max_price";
            } else {
                $total_items_query .= " WHERE a.price BETWEEN $min_price AND $max_price";
                $where_added = true;
            }
        }
    }
}

$result_total_items = $conn->query($total_items_query);
$total_items_row = $result_total_items->fetch_assoc();
$total_items = $total_items_row['total'];

$total_pages = ceil($total_items / $limit);

$total_rows_result = mysqli_query($conn, $total_rows_sql);
$total_rows = mysqli_fetch_assoc($total_rows_result)['total'];

$sorting = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$pages = ceil($total_rows / $limit);
$offset = ($page - 1) * $limit;

// Sort by prod_no
$sortby_column  = isset($_GET['sortby']) ? $_GET['sortby'] : 'prod_name';
$sql = "SELECT a.*, b.category_name FROM products a INNER JOIN category b ON a.category_id=b.category_id";
$where_added = false;

if (!empty($search)) {
    $sql .= " WHERE a.prod_name LIKE '%$search%'";
    $where_added = true;
}

if (isset($_GET['category']) && is_array($_GET['category']) && !empty($_GET['category'])) {
    $selected_categories = $_GET['category'];

    $escaped_categories = array_map(function ($category) use ($conn) {
        return mysqli_real_escape_string($conn, $category);
    }, $selected_categories);

    $category_list = "'" . implode("','", $escaped_categories) . "'";

    if ($where_added) {
        $sql .= " AND b.category_name IN ($category_list)";
    } else {
        $sql .= " WHERE b.category_name IN ($category_list)";
        $where_added = true;
    }
}

if (isset($_GET['price'])) {
    $price_range = explode(' - ', $_GET['price']);
    if (count($price_range) === 2) {
        $min_price = filter_var(str_replace('₱', '', $price_range[0]), FILTER_VALIDATE_FLOAT);
        $max_price = filter_var(str_replace('₱', '', $price_range[1]), FILTER_VALIDATE_FLOAT);

        if ($min_price !== false && $max_price !== false && $min_price >= 0 && $max_price >= $min_price) {
            if ($where_added) {
                $sql .= " AND a.price BETWEEN $min_price AND $max_price";
            } else {
                $sql .= " WHERE a.price BETWEEN $min_price AND $max_price";
                $where_added = true;
            }
        }
    }
}

$sql .= " ORDER BY $sortby_column $sorting
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

$start_index = $offset + 1;
$end_index = min($offset + $limit, $total_rows);


?>
<!-- Shop Start -->
<div class="shop-section section-padding-2">
    <div class="container-fluid custom-container">
        <!-- Shop Wrapper Start -->
        <div class="shop-wrapper">
            <div class="row gy-5">
                <div class="col-lg-3">
                    <!-- Sidebar Shop Filter widget Start -->
                    <div class="sidebar-shop-filter-widget">
                        <!-- Sidebar widget Item Start -->
                        <?php
                        $query = "SELECT category_name FROM category";
                        $result1 = mysqli_query($conn, $query);
                        ?>
                        <div class="sidebar-widget-item">
                            <h4 class="sidebar-widget-item__title">Category</h4>
                            <form action="" method="get">
                                <div class="sidebar-widget-item__filter category-filter">
                                    <ul class="sidebar-widget-item__list category">
                                        <?php
                                        echo '<form action="" method="get">';

                                        while ($row = mysqli_fetch_assoc($result1)) {
                                            $category_name = htmlspecialchars($row['category_name'], ENT_QUOTES, 'UTF-8');
                                            $category_id = 'category-' . strtolower(str_replace(' ', '-', $category_name));

                                            $checked = '';
                                            if (isset($_GET['category']) && in_array($category_name, $_GET['category'])) {
                                                $checked = 'checked';
                                            }
                                        ?>
                                            <li>
                                                <input type="checkbox" id="<?php echo $category_id; ?>" name="category[]" value="<?php echo $category_name; ?>" <?php echo $checked; ?> />
                                                <label for="<?php echo $category_id; ?>">
                                                    <span></span><?php echo $category_name; ?>
                                                </label>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <?php
                                if (isset($_GET['category'])) { ?>
                                    <button type="button" onclick="location.replace('flowers.php')" class="filter-price-btn mt-3">Clear</button>
                                <?php   }
                                ?>
                                <button type="submit" class="filter-price-btn mt-3">Filter</button>
                            </form>


                        </div>
                        <!-- Sidebar widget Item End -->

                        <!-- Sidebar widget Item Start -->
                     
                        <!-- Sidebar widget Item End -->

    
                    </div>
                    <!-- Sidebar Shop Filter widget End -->
                </div>
                <div class="col-lg-9">
                    <!-- Shop Filter Start -->
                    <div class="shop-filter">
                        <!-- Shop Filter Default Start -->
                        <div class="shop-filter-default justify-content-between align-items-center">
                            <!-- Shop Filter Count Start -->
                            <div class="shop-filter-count d-none d-sm-block">
                                <p>Showing <?= $start_index ?> - <?= $end_index ?> of <?= $total_rows ?> results</p>
                            </div>
                            <!-- Shop Filter Count End -->

                            <!-- Shop Filter Sort By Start -->
                            <div class="shop-filter-sort-by">
                                <div class="shop-filter-sort-by__label">
                                    <span><?php if (!isset($_GET['sortby'])) echo 'Sort by Default'; ?>
                                        <?php if (isset($_GET['sortby']) && $_GET['sortby'] == 'prod_name') echo 'Sort by Name'; ?>
                                        <?php if (isset($_GET['sortby']) && $_GET['sortby'] == 'price' && isset($_GET['sort']) && $_GET['sort'] == "asc") echo 'Sort by Price'; ?>
                                        <?php if (isset($_GET['sortby']) && $_GET['sortby'] == 'price' && isset($_GET['sort']) && $_GET['sort'] == "desc") echo 'Sort by Price'; ?>
                                    </span>
                                    <i class="lastudioicon-down-arrow"></i>
                                </div>
                                <ul class="shop-filter-sort-by__dropdown">
                                    <li class="<?php if (!isset($_GET['sortby'])) echo 'active'; ?>">
                                        <a href="/flowers.php">Sort by Default</a>
                                    </li>
                                    <li class="<?php if (isset($_GET['sortby']) && $_GET['sortby'] == 'prod_name') echo 'active'; ?>">
                                        <a href="?sortby=prod_name">Sort by Name</a>
                                    </li>
                                    <li class="<?php if (isset($_GET['sortby']) && $_GET['sortby'] == 'price' && isset($_GET['sort']) && $_GET['sort'] == "asc") echo 'active'; ?>">
                                        <a href="?sortby=price&sort=asc">Sort by Price:
                                            <i class="lastudioicon-arrow-up"></i></a>
                                    </li>
                                    <li class="<?php if (isset($_GET['sortby']) && $_GET['sortby'] == 'price' && isset($_GET['sort']) && $_GET['sort'] == "desc") echo 'active'; ?>">
                                        <a href="?sortby=price&sort=desc">Sort by Price:
                                            <i class="lastudioicon-arrow-down"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Shop Filter Sort By End -->
                        </div>
                        <!-- Shop Filter Default End -->
                    </div>
                    <!-- Shop Filter End -->

                    <div class="row">
                        <?php
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
                                                    <img src="prodimg/<?= $row['prod_img'] ?>" alt="Product" width="392" height="400" loading="lazy" />
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
                                                                <?php }  ?>


                                                            </div>
                                                            <div class="product-single-content__meta">
                                                                <div class="product-single-content__meta--item">
                                                                    <div class="label">Categories:</div>
                                                                    <div class="content">
                                                                        <a href="#"><?= $row['category_name'] ?></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="product-single-content__meta">
                                                                <div class="product-single-content__meta--item">
                                                                    <div class="label">Stock:</div>
                                                                    <div class="content">
                                                                        <a href="#"><?= $row['stock'] ?></a>
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

                    <!-- Pagination Start -->
                    <div class="paginations">
                        <ul class="paginations-list">
                            <?php if ($page > 1) : ?>
                                <?php
                                $prev_page_url = '?' . http_build_query(array_merge($_GET, ['page' => $page - 1]));
                                ?>

                                <li><a href="<?= $prev_page_url ?>">
                                        <i class="lastudioicon-arrow-left"></i>
                                    </a></li>
                            <?php endif; ?>

                            <?php if ($total_pages <= 5) : ?>
                                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                    <?php
                                    $page_url = '?' . http_build_query(array_merge($_GET, ['page' => $i]));
                                    ?>
                                    <li><a href="<?= $page_url ?>" class="<?= $page == $i ? 'active' : ''; ?>"><?= $i; ?></a></li>
                                <?php endfor; ?>
                            <?php else : ?>
                                <?php if ($page <= 3) : ?>
                                    <?php for ($i = 1; $i <= 3; $i++) : ?>
                                        <?php
                                        $page_url = '?' . http_build_query(array_merge($_GET, ['page' => $i]));
                                        ?>
                                        <li><a href="<?= $page_url ?>" class="<?= $page == $i ? 'active' : ''; ?>"><?= $i; ?></a></li>
                                    <?php endfor; ?>
                                    <li><span>...</span></li>
                                    <?php
                                    $last_page_url = '?' . http_build_query(array_merge($_GET, ['page' => $total_pages]));
                                    ?>
                                    <li><a href="<?= $last_page_url ?>"><?= $total_pages; ?></a></li>
                                <?php elseif ($page >= $total_pages - 2) : ?>
                                    <?php
                                    $first_page_url = '?' . http_build_query(array_merge($_GET, ['page' => 1]));
                                    ?>
                                    <li><a href="<?= $first_page_url ?>">1</a></li>
                                    <li><span>...</span></li>
                                    <?php for ($i = $total_pages - 2; $i <= $total_pages; $i++) : ?>
                                        <?php
                                        $page_url = '?' . http_build_query(array_merge($_GET, ['page' => $i]));
                                        ?>
                                        <li><a href="<?= $page_url ?>" class="<?= $page == $i ? 'active' : ''; ?>"><?= $i; ?></a></li>
                                    <?php endfor; ?>
                                <?php else : ?>
                                    <?php
                                    $first_page_url = '?' . http_build_query(array_merge($_GET, ['page' => 1]));
                                    ?>
                                    <li><a href="<?= $first_page_url ?>">1</a></li>
                                    <li><span>...</span></li>
                                    <?php for ($i = $page - 1; $i <= $page + 1; $i++) : ?>
                                        <?php
                                        $page_url = '?' . http_build_query(array_merge($_GET, ['page' => $i]));
                                        ?>
                                        <li><a href="<?= $page_url ?>" class="<?= $page == $i ? 'active' : ''; ?>"><?= $i; ?></a></li>
                                    <?php endfor; ?>
                                    <li><span>...</span></li>
                                    <?php
                                    $last_page_url = '?' . http_build_query(array_merge($_GET, ['page' => $total_pages]));
                                    ?>
                                    <li><a href="<?= $last_page_url ?>"><?= $total_pages; ?></a></li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if ($page < $total_pages) : ?>
                                <?php
                                $next_page_url = '?' . http_build_query(array_merge($_GET, ['page' => $page + 1]));
                                ?>
                                <li>
                                    <a href="<?= $next_page_url ?>">
                                        <i class="lastudioicon-arrow-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <!-- Pagination End -->
                </div>

            </div>
        </div>
        <!-- Shop Wrapper End -->
    </div>
</div>
<!-- Shop End -->

<?php include('layouts/footer.php') ?>
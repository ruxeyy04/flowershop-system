    <!-- Search Start -->
    <div class="search-modal modal fade" id="SearchModal">
        <!-- Search Close Start -->
        <button class="search-modal__close" data-bs-dismiss="modal" aria-label="remove">
            <i class="lastudioicon-e-remove"></i>
        </button>
        <!-- Search Close End  -->

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Search Form Start  -->
                <div class="search-modal__form">
                    <form action="flowers.php" method="get">
                        <input type="text" placeholder="Search product…" name="search"/>
                        <button class="" aria-label="search">
                            <i class="lastudioicon-zoom-1"></i>
                        </button>
                    </form>
                </div>
                <!-- Search Form End  -->
            </div>
        </div>
    </div>

    <!-- Search End -->

    <!-- Mobile Menu Start -->
    <div class="mobile-menu offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
        <!-- offcanvas-header Start -->
        <div class="offcanvas-header">
            <button type="button" class="mobile-menu__close" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="lastudioicon-e-remove"></i>
            </button>
        </div>
        <!-- offcanvas-header End -->

        <!-- offcanvas-body Start -->
        <div class="offcanvas-body">
            <nav class="navbar-mobile-menu">
                <ul class="mobile-menu-items">
                    <li>
                        <a class="active" href="index.php">
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a class="" href="about.php">
                            <span>About</span>
                        </a>
                    </li>
                    <li>
                        <a class="" href="contact.php">
                            <span>Contact</span>
                        </a>
                    </li>
                    <li>
                        <a class="" href="flowers.php">
                            <span>Flowers</span>
                        </a>
                    </li>
                    <?php
                    if (isset($_SESSION['userid'])) { ?>
                        <li>
                            <a href="#">
                                Profile
                                <span class="menu-expand" aria-label="down-arrow">
                                    <i class="lastudioicon-down-arrow"></i>
                                </span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="my-account.php">
                                        <span>My Account</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="orders.php">
                                        <span>My Orders</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="?logout">
                                        <span>Logout</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <li>
                            <a class="" href="login-register.php">
                                <span>Login</span>
                            </a>
                        </li>
                    <?php  }
                    ?>

                </ul>
            </nav>
        </div>
        <!-- offcanvas-body end -->
    </div>

    <!-- Mobile Menu End -->

    <!-- Mobile Meta Start -->
    <div class="mobile-meta d-md-none">
        <ul class="mobile-meta-items">
            <li>
                <button data-bs-toggle="modal" data-bs-target="#SearchModal" aria-label="search">
                    <i class="lastudioicon-zoom-1"></i>
                </button>
            </li>
            <?php
            if (isset($_SESSION['userid'])) { ?>
                <?php

                $userId = $_SESSION['userid'];
                // Assuming you have a connection to the database in $conn
                $query = "SELECT carts.*, products.*, carts.quantity AS cart_quantity
FROM carts 
JOIN products ON carts.prod_no = products.prod_no 
WHERE carts.userid = ?";

                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                ?>
                    <li>
                        <button data-bs-toggle="offcanvas" data-bs-target="#cartSidebar" aria-label="cart">
                            <i class="lastudioicon-shopping-cart-1"></i>
                            <span class="badge"><?= $result->num_rows ?></span>
                        </button>
                    </li>
                <?php } ?>

            <?php }
            ?>

        </ul>
    </div>

    <!-- Mobile Meta End -->
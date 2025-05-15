    <!-- Header Start -->
    <header class="header bg-white header-height">

        <!-- Header Main Start -->
        <div class="header__main header__main-dark header-shadow d-flex align-items-center">
            <div class="container-fluid custom-container">
                <div class="row align-items-center position-relative">
                    <div class="col-md-4 col-3 d-xl-none">
                        <button class="header__main--toggle" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-label="menu">
                            <i class="lastudioicon-menu-8-1"></i>
                        </button>
                    </div>
                    <div class="col-xl-3 col-md-4 col-6">
                        <div class="header__main--logo text-center text-xl-start" style="height: 32px;
">
                            <a href="index.php">
                                <style>
                                    .logo-container {
                                        width: 280px;
                                        height: 60px;
                                        background-image: url('icon/logo.png');
                                        background-size: contain;
                                        background-position: center;
                                        background-repeat: no-repeat;
                                        display: flex;
                                        align-items: center;
                                    }

                                </style>
                            <div class="logo-container"></div>

                            </a>
                        </div>
                    </div>
                    <div class="col-xl-6 d-none d-xl-block">
                        <nav class="header__main--menu position-static">
                            <!-- Menu Item List Start -->
                            <ul class="menu-items-list menu-items-list--dark d-flex justify-content-center">
                                <li>
                                    <a class="" href="index.php">
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
                                            <span>Profile</span>
                                            <i class="lastudioicon-down-arrow" aria-hidden="true"></i>
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
                            <!-- Menu Item List End -->
                        </nav>
                    </div>
                    <div class="col-xl-3 col-md-4 col-3">
                        <div class="header__main--meta d-flex justify-content-end align-items-center">
                            <!-- Meta Item List Start -->
                            <ul class="meta-items-list meta-items-list--dark d-flex justify-content-end align-items-center">
                                <li class="search d-none d-lg-block">
                                    <form action="flowers.php" method="get">
                                        <div class="meta-search meta-search--dark">
                                            <input type="text" placeholder="Search products…" name="search"/>
                                            <button aria-label="search">
                                                <i class="lastudioicon-zoom-1"></i>
                                            </button>
                                        </div>
                                    </form>
                                </li>
                                <?php
                                if (isset($_SESSION['userid'])) { ?>
                                    <li class="cart">
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
                                            <button data-bs-toggle="offcanvas" data-bs-target="#cartSidebar" aria-label="Cart">
                                                <i class="lastudioicon-shopping-cart-1"></i><span class="badge"><?= $result->num_rows ?></span>
                                            </button>
                                        <?php } ?>

                                    </li>
                                <?php }
                                ?>

                            </ul>
                            <!-- Meta Item List Start -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header Main End -->
    </header>

    <!-- Header End -->
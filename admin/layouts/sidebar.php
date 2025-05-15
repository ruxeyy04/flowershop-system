<?php
$current_page = basename($_SERVER['PHP_SELF']);

$menu_items = array(
    "index.php" => "Dashboard",
    "orders.php" => "Orders",
    "users.php" => "Users",
    "products.php" => "Products",
    "transactions.php" => "Transactions",
);

function is_active($page, $current_page)
{
    if ($page === $current_page) {
        echo 'active';
    }
}

?>
<div class="nav-left-sidebar sidebar-dark">
    <div class="menu-list">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="d-xl-none d-lg-none" href="#">Dashboard</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav flex-column">

                    <li class="nav-divider">
                        Menu
                    </li>
                    <?php foreach ($menu_items as $href => $label) : ?>
                        <li class="nav-item">
                            <a href="<?php echo $href; ?>" class="<?php is_active($href, $current_page); ?> nav-link">
                                
                                    <?php if ($label === "Dashboard") : ?>
                                        <i class="fas fa-chart-line"></i>
                                    <?php elseif ($label === "Orders") : ?>
                                        <i class="fas fa-box"></i>
                                    <?php elseif ($label === "Users") : ?>
                                        <i class="fa fa-fw fa-user-circle"></i>
                                    <?php elseif ($label === "Products") : ?>
                                        <i class="fas fa-archive"></i>
                                    <?php elseif ($label === "Transactions") : ?>
                                        <i class="fas fa-hand-holding-usd"></i>
                                    <?php endif; ?>
                                
                                <?php echo $label; ?>
                            </a>

                        </li>
                    <?php endforeach; ?>

                </ul>
            </div>


    </div>
    </nav>
</div>
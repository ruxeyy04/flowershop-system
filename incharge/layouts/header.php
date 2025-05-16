<?php
require('../config.php');
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;

if (isset($_SESSION['userid'])) {
    $userinfo_sql = "SELECT * FROM userinfo WHERE userid = ?";
    $userinfo_stmt = $conn->prepare($userinfo_sql);
    $userinfo_stmt->bind_param("i", $userid);
    $userinfo_stmt->execute();
    $userinfo_res = $userinfo_stmt->get_result();
    $userinfo = $userinfo_res->fetch_assoc();
}
if (isset($_SESSION['usertype'])) {
    if ($_SESSION['usertype'] == 'Admin') {
        echo '<meta http-equiv="refresh" content="0;url=/admin/index.php">';
        exit();
    } else if ($_SESSION['usertype'] == 'Client') {
        echo '<meta http-equiv="refresh" content="0;url=/index.php">';
        exit();
    }
}
if (!isset($_SESSION['userid'])) {
    echo '<meta http-equiv="refresh" content="0;url=/login-register.php">';
    exit();
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/libs/css/style.css">
    <link rel="stylesheet" href="assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="assets/vendor/charts/chartist-bundle/chartist.css">
    <link rel="stylesheet" href="assets/vendor/charts/morris-bundle/morris.css">
    <link rel="stylesheet" href="assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendor/charts/c3charts/c3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/7.2.3/css/flag-icons.min.css">
    <title>Blossom Box - Incharge</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    </script>
</head>

<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        <!-- ============================================================== -->
        <!-- navbar -->
        <!-- ============================================================== -->
        <div class="dashboard-header">
            <nav class="navbar navbar-expand-lg bg-white fixed-top">
                <a class="navbar-brand" href="index.php">Blossom Box</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse " id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-right-top">
                        <li class="nav-item">
                            <form action="<?php echo in_array(basename($_SERVER['PHP_SELF']), array('transactions.php', 'orders.php', 'users.php', 'products.php')) ? basename($_SERVER['PHP_SELF']) : 'products.php'; ?>" method="GET">
                                <div id="custom-search" class="top-search-bar">
                                    <input class="form-control" type="text" placeholder="Search.." name="search">
                                </div>
                            </form>
                        </li>
                        <li class="nav-item dropdown nav-user">
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="/profile_img/<?=$userinfo['image'] != null ? $userinfo['image'] : 'default.jpg'?>" alt="" class="user-avatar-md rounded-circle"></a>
                            <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                <div class="nav-user-info">
                                    <h5 class="mb-0 text-white nav-user-name"><?=$userinfo['fname']?> <?=$userinfo['lname']?> </h5>
                                    <span class="status"></span><span class="ml-2"><?=$userinfo['usertype']?></span>
                                </div>
                                <a class="dropdown-item" href="profile.php"><i class="fas fa-user mr-2"></i>Profile Setting</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#inchargeLogoutModal"><i class="fas fa-power-off mr-2"></i>Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- ============================================================== -->
        <!-- end navbar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- left sidebar -->
        <!-- ============================================================== -->
        <?php include("layouts/sidebar.php"); ?>
    </div>
    <!-- ============================================================== -->
    <!-- end left sidebar -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- wrapper  -->
    <!-- ============================================================== -->
    <div class="dashboard-wrapper">

<!-- Incharge Logout Confirmation Modal -->
<div class="modal fade" id="inchargeLogoutModal" tabindex="-1" role="dialog" aria-labelledby="inchargeLogoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="inchargeLogoutModalLabel">Confirm Logout</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to logout?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        <a href="?logout" class="btn btn-primary">Yes</a>
      </div>
    </div>
  </div>
</div>
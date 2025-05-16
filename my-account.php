<?php include('layouts/header.php');

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION['userid']) || !isset($userinfo)) {
    header("Location: /login-register.php");
    exit();
}
?>
<!-- Breadcrumb Start -->
<div class="breadcrumb-section">
    <div class="container-fluid custom-container">
        <div class="breadcrumb-wrapper text-center">
            <h2 class="breadcrumb-wrapper__title">My Account</h2>
            <ul class="breadcrumb-wrapper__items justify-content-center">
                <li><a href="/">Home</a></li>
                <li><span>My Account</span></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- My Account Start -->
<div class="my-account-section section-padding-2">
    <div class="container-fluid custom-container">
        <!-- My Account Tabs Start -->
        <div class="my-account-tab">
            <!-- My Account Tabs Menu Start -->
            <div class="my-account-tab__menu">
                <ul class="nav justify-content-center">
                    <li>
                        <button class="account-btn" data-bs-toggle="tab" data-bs-target="#dashboard" type="button">
                            Dashboard
                        </button>
                    </li>
                    <li>
                        <button class="account-btn active" data-bs-toggle="tab" data-bs-target="#account-detail" type="button">
                            Account Detail
                        </button>
                    </li>
                    <li>
                        <button class="account-btn" data-bs-toggle="modal" data-bs-target="#logoutModal" type="button">
                            Logout
                        </button>
                    </li>
                </ul>
            </div>
            <!-- My Account Tabs Menu End -->

            <div class="tab-content">
                <div class="tab-pane fade" id="dashboard">
                    <!-- My Account Dashboard Start -->
                    <div class="my-account-dashboard">
                        <p>
                            Hello <strong><?= $userinfo['username'] ?></strong> (not
                            <strong><?= $userinfo['username'] ?></strong>?
                            <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Log out</a>)
                        </p>
                        <p>
                            From your account dashboard you can view
                            your account and manage the information of the account detail.
                        </p>
                    </div>
                    <!-- My Account Dashboard End -->
                </div>
                <div class="tab-pane fade  show active" id="account-detail">
                    <!-- My Account Account Detail Start -->
                    <div class="my-account-detail">
                                        <div class="d-flex ">
                    <figure class="mr-4 flex-shrink-0">
                        <div style="background-image: url('/profile_img/<?= $userinfo['image'] == null ? 'default.jpg' : $userinfo['image'] ?>'); background-position: center;
                                background-size: cover;
                                height: 100px;
                                width: 100px;
                                border-radius: 50%;
                                box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;">
                        </div>
                    </figure>
                    <div class="flex-fill">
                        <h5 class="mb-3"><?= $userinfo['fname'] ?> <?= $userinfo['lname'] ?></h5>
                        <?php
                        if (isset($_GET['editimage']) != 1) { ?>
                            <a href="my-account.php?editimage=1" class="btn btn-primary me-2">Edit Image</a>
                        <?php } else { ?>
                            <form action="" method="post" enctype="multipart/form-data" class="d-flex align-items-center">
                                <div class="">
                                    <input class="form-control" type="file" id="formFile" name="profile_img" accept="image/*">
                                </div>
                                <div class="d-flex align-items-center justify-content-center ms-2">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-danger" onclick="location.replace('my-account.php')">Cancel</button>
                                        <button type="submit" class="btn btn-success" name="edit_pic">Update Image</button>
                                    </div>
                                </div>
                            </form>
                        <?php }
                        ?>
                    </div>
                    <?php
                    if (isset($_POST['edit_pic'])) {
                        if ($_FILES['profile_img']['name'] != '') {
                            $file_name = $_FILES['profile_img']['name'];
                            $file_temp = $_FILES['profile_img']['tmp_name'];
                            $upload_dir = 'profile_img/';

                            $check = getimagesize($file_temp);
                            if ($check === false) {
                                $_SESSION['alert'] = "<script>
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'File is not an image.',
                                    });
                                </script>";
                                echo '<meta http-equiv="refresh" content="0;url=my-account.php">';
                                exit();
                            }

                            if ($_FILES['profile_img']['size'] > 5000000) { // 5MB limit
                                $_SESSION['alert'] = "<script>
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'Sorry, your file is too large. 5MB Limit',
                                    });
                                </script>";
                                echo '<meta http-equiv="refresh" content="0;url=my-account.php">';
                                exit();
                            }

                            $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                            if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
                                $_SESSION['alert'] = "<script>
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.',
                                    });
                                </script>";
                                echo '<meta http-equiv="refresh" content="0;url=my-account.php">';
                                exit();
                            }

                            $unique_name = uniqid() . '.' . $imageFileType;
                            $target_file = $upload_dir . $unique_name;

                            if (move_uploaded_file($file_temp, $target_file)) {
                                $img = $unique_name;
                            } else {
                                $_SESSION['alert'] = "<script>
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'Sorry, there was an error uploading your file.',
                                    });
                                </script>";
                                echo '<meta http-equiv="refresh" content="0;url=my-account.php">';
                                exit();
                            }
                        } else {
                            $img = "default.jpg";
                        }


                        $sql = "UPDATE userinfo SET image='$img' WHERE userid='$userid'";

                        if ($conn->query($sql) === TRUE) {
                            $_SESSION['alert'] = "<script>
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Profile image updated successfully.',
                                });
                            </script>";
                            echo '<meta http-equiv="refresh" content="0;url=my-account.php">';
                            exit();
                        } else {
                            $_SESSION['alert'] = "<script>
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Error updating record: $conn->error',
                                });
                            </script>";
                            echo '<meta http-equiv="refresh" content="0;url=my-account.php">';
                        }

                        $conn->close();
                    }
                    ?>
                </div>
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- Single Form Start -->
                                    <div class="single-form">
                                        <label class="single-form__label">First name *</label>
                                        <input class="single-form__input" type="text" name="fname" required  value="<?=$userinfo['fname']?>"/>
                                    </div>
                                    <!-- Single Form Start -->
                                </div>
                                <div class="col-md-4">
                                    <!-- Single Form Start -->
                                    <div class="single-form">
                                        <label class="single-form__label">Middle name *</label>
                                        <input class="single-form__input" type="text" name="midname" value="<?=$userinfo['midname']?>"/>
                                    </div>
                                    <!-- Single Form Start -->
                                </div>
                                <div class="col-md-4">
                                    <!-- Single Form Start -->
                                    <div class="single-form">
                                        <label class="single-form__label">Last name *</label>
                                        <input class="single-form__input" type="text" name="lname" required value="<?=$userinfo['lname']?>"/>
                                    </div>
                                    <!-- Single Form Start -->
                                </div>
                            </div>
                            <!-- Single Form Start -->
                            <div class="single-form">
                                <label class="single-form__label">Username *</label>
                                <input class="single-form__input" type="text" name="username" required value="<?=$userinfo['username']?>"/>
                            </div>
                            <!-- Single Form Start -->

                            <!-- Single Form Start -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="single-form">
                                        <label class="single-form__label">Email address *</label>
                                        <input class="single-form__input" type="email" name="email" required value="<?=$userinfo['email']?>"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="single-form">
                                        <label class="single-form__label">Contact Info *</label>
                                        <input class="single-form__input" type="text" name="contact" required value="<?=$userinfo['contact_number']?>"/>
                                    </div>
                                </div>
                            </div>
                            <!-- Single Form Start -->
                            <div class="single-form">
                                <button class="single-form__btn btn" type="submit" name="updateprofile">
                                    Save Info
                                </button>
                            </div>
                        </form>
                        <!-- Single Form Start -->
                        <!-- Single Form Start -->

                        <p class="my-account-detail__legend">
                            Password change
                        </p>

                        <form action="" method="post">
                            <!-- Single Form Start -->
                            <div class="single-form">
                                <label class="single-form__label">Current password (leave blank
                                    to leave unchanged)</label>
                                <input class="single-form__input" type="password" name="oldpass"/>
                            </div>
                            <!-- Single Form Start -->
                            <!-- Single Form Start -->
                            <div class="single-form">
                                <label class="single-form__label">New password (leave blank to
                                    leave unchanged)</label>
                                <input class="single-form__input" type="password"  name="newpass"/>
                            </div>
                            <!-- Single Form Start -->
                            <!-- Single Form Start -->
                            <div class="single-form">
                                <label class="single-form__label">Confirm new password</label>
                                <input class="single-form__input" type="password" name="confirmpass"/>
                            </div>
                            <!-- Single Form Start -->
                            <!-- Single Form Start -->
                            <div class="single-form">
                                <button class="single-form__btn btn" type="submit" name="updatepassword">
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- My Account Account Detail End -->
                </div>
            </div>
        </div>
        <!-- My Account Tabs End -->
    </div>
</div>
<?php

if (isset($_POST['updateprofile'])) {
    $fname = $_POST['fname'];
    $midname = $_POST['midname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['contact'];


    $sql_profile = "UPDATE userinfo SET 
        fname = ?, 
        midname = ?, 
        lname = ?, 
        contact_number = ?, 
        email = ?, 
        username = ? 
        WHERE userid = ?";

    $userinfo = $conn->prepare($sql_profile);
    $userinfo->bind_param("ssssssi", $fname, $midname, $lname, $phone, $email, $username, $userid);

    if ($userinfo->execute() === TRUE) {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'success',
            title: 'Successfully Updated Profile',
        });
    </script>";
        echo '<meta http-equiv="refresh" content="0;url=my-account.php">';
        exit();
    } else {
        $_SESSION['alert'] = "<script>
        Toast.fire({
            icon: 'errpr',
            title: '$conn->error',
        });
    </script>";
        echo '<meta http-equiv="refresh" content="0;url=my-account.php">';
        exit();
    }
    $userinfo->close();
    $conn->close();
}
?>
<?php
if (isset($_POST['updatepassword'])) {
    $oldpass = $_POST['oldpass'];
    $newpass = $_POST['newpass'];
    $confirmpass = $_POST['confirmpass'];

    $sql_get_password = "SELECT password FROM userinfo WHERE userid = ?";
    $stmt = $conn->prepare($sql_get_password);
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($current_password);
    $stmt->fetch();

    if ($oldpass === $current_password) {
        if ($newpass === $confirmpass) {
            $sql_update_password = "UPDATE userinfo SET password = ? WHERE userid = ?";
            $update_stmt = $conn->prepare($sql_update_password);
            $update_stmt->bind_param("si", $newpass, $userid);

            if ($update_stmt->execute() === TRUE) {
                $_SESSION['alert'] = "<script>
                        Toast.fire({
                            icon: 'success',
                            title: 'Successfully Updated Password',
                        });
                    </script>";
                echo '<meta http-equiv="refresh" content="0;url=my-account.php">';
                exit();
            } else {
                $_SESSION['alert'] = "<script>
                Toast.fire({
                    icon: 'error',
                    title: '{$conn->error}',
                });
            </script>";
                echo '<meta http-equiv="refresh" content="0;url=my-account.php">';
                exit();
            }
            $update_stmt->close();
        } else {
            $_SESSION['alert'] = "<script>
                Toast.fire({
                    icon: 'error',
                    title: 'New password and confirmation do not match',
                });
            </script>";
            echo '<meta http-equiv="refresh" content="0;url=my-account.php">';
            exit();
        }
    } else {
        $_SESSION['alert'] = "<script>
            Toast.fire({
                icon: 'error',
                title: 'Old password is incorrect',
            });
        </script>";
        echo '<meta http-equiv="refresh" content="0;url=my-account.php">';
        exit();
    }
    $stmt->close();
    $conn->close();
}
?>

<!-- My Account End -->

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to logout?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <a href="?logout" class="btn btn-primary">Yes</a>
      </div>
    </div>
  </div>
</div>

<?php include('layouts/footer.php') ?>
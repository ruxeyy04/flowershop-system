<?php include('layouts/header.php') ?>
<!-- Breadcrumb Start -->
<div class="breadcrumb-section">
    <div class="container-fluid custom-container">
        <div class="breadcrumb-wrapper text-center">
            <h2 class="breadcrumb-wrapper__title">
                Log In & Register
            </h2>
            <ul class="breadcrumb-wrapper__items justify-content-center">
                <li><a href="index.php">Home</a></li>
                <li><span>Log In & Register</span></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<?php
// Display alert message if it exists
if (isset($_SESSION['alert'])) {
    echo $_SESSION['alert'];
    unset($_SESSION['alert']);
}
?>

<!-- Log In & Register Start -->
<div class="login-register-section section-padding-2">
    <div class="container-fluid custom-container">
        <div class="row">
            <div class="col-md-6">
                <!-- Log In & Register Box Start -->
                <div class="login-register">
                    <h3 class="login-register__title">Log In</h3>

                    <form action="#" method="post">
                        <div class="login-register__form">
                            <div class="single-form">
                                <input class="single-form__input" name="uname" type="text" placeholder="Username *" required />
                            </div>
                            <div class="single-form">
                                <input class="single-form__input" name="pass" type="password" placeholder="Password *" required />
                            </div>
                            <div class="single-form">
                                <button class="single-form__btn btn" type="submit" name="login">
                                    Log In
                                </button>
                            </div>
                        </div>
                    </form>
                    <?php

                    if (isset($_POST['login'])) {
                        $uname = $_POST['uname'];
                        $password = $_POST['pass'];

                        // Query to check username and password
                        $sql = "SELECT userid, usertype FROM userinfo WHERE username = '$uname' AND password = '$password'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $usertype = $row['usertype'];
                            $userid = $row['userid'];
                            $_SESSION['userid'] = $userid;
                            $_SESSION['usertype'] = $usertype;
                            switch ($usertype) {
                                case 'Admin':
                                    echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: 'Welcome Admin',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/admin/';
                        }
                    });
                </script>";
                                    break;
                                case 'Incharge':
                                    echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: 'Welcome Incharge',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/incharge/';
                        }
                    });
                </script>";
                                    break;
                                case 'Client':
                                    echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: 'Welcome Customer',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/index.php';
                        }
                    });
                </script>";
                                    break;
                                default:
                                    echo "<script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'error',
                                                    text: 'Unknown usertype',
                                                    allowOutsideClick: false
                                                })
                                            </script>";
                            }
                        } else {
                            echo "<script>
        Swal.fire({
            icon: 'info',
            title: 'Invalid',
            text: 'Invalid username or password',
        })
    </script>";
                        }

                        $conn->close();
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="login-register">
                    <h3 class="login-register__title">Register</h3>

                    <form action="#" method="post">
                        <div class="login-register__form">
                            <div class="single-form">
                                <input class="single-form__input" name="fname" type="text" placeholder="First Name *" required />
                            </div>
                            <div class="single-form">
                                <input class="single-form__input" name="lname" type="text" placeholder="Last Name *" required />
                            </div>
                            <div class="single-form">
                                <input class="single-form__input" name="uname" type="text" placeholder="Username *" required />
                            </div>
                            <div class="single-form">
                                <input class="single-form__input" name="email" type="email" placeholder="Email address *" required />
                            </div>
                            <div class="single-form">
                                <input class="single-form__input" name="contact" type="text" placeholder="Contact Number *" required />
                            </div>
                            <div class="single-form">
                                <input class="single-form__input" name="password" type="password" placeholder="Password *" required />
                            </div>
                            <div class="single-form">
                                <button class="single-form__btn btn" type="submit" name="register">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                    <?php
                    if (isset($_POST['register'])) {
                        // Database connection code (assumed $conn is already established)
                        $fname = $_POST['fname'];
                        $lname = $_POST['lname'];
                        $username = $_POST['uname'];
                        $email = $_POST['email'];
                        $contact = $_POST['contact'];
                        $password = $_POST['password'];

                        $sql = "INSERT INTO `userinfo`(`fname`, `lname`, `username`, `email`, `contact_number`, `password`) VALUES ('$fname', '$lname', '$username', '$email', '$contact', '$password')";
                        if ($conn->query($sql) === TRUE) {
                            echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'New account registered successfully',
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = 'login-register.php';
                                    }
                                });
                            </script>";
                        } else {
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error: " . $sql . "<br>" . $conn->error . "'
                                });
                            </script>";
                        }

                        $conn->close();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Log In & Register End -->
<?php include('layouts/footer.php') ?>
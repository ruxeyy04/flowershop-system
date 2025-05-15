</main>

<!-- Footer Start -->
<footer class="footer-section">
    <div class="container-fluid custom-container">
        <!-- Footer Main Start -->
        <div class="footer-main">
            <div class="footer-col-1 align-self-center">
                <!-- Footer About Start -->
                <div class="footer-about text-xxl-start text-center mx-xxl-0 mx-auto">
                    <a class="logo justify-content-xxl-start justify-content-center" href="#">
                        <img src="icon/logo.png" alt="Logo"  height="100" />
                    </a>
                </div>
                <!-- Footer About End -->
            </div>
            <div class="footer-col-2">
                <!-- Footer Link Start -->
                <div class="footer-link">
                    <div class="footer-link__wrapper">
                        <h2 class="footer-title">Pages</h2>

                        <ul class="footer-link__list">
                            <li><a href="about.php">About Us</a></li>
                            <li><a href="products.php">Products</a></li>
                            <li><a href="contact.php">Contact</a></li>
                        </ul>
                    </div>
                    <div class="footer-link__wrapper">
                        <h2 class="footer-title">Category</h2>

                        <ul class="footer-link__list">
                            <?php
                            $category_query = "SELECT category_name FROM category";
                            $category_result = mysqli_query($conn, $category_query);
                            
                            while ($category = mysqli_fetch_assoc($category_result)) {
                                echo '<li><a href="flowers.php?category[]=' . urlencode($category['category_name']) . '">' . htmlspecialchars($category['category_name']) . '</a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="footer-link__wrapper">
                        <h2 class="footer-title">Contact</h2>

                        <ul class="footer-link__list">
                            <li>
                                <span>
                                H.T Feliciano St. Aguada <br> Ozamiz City
                            </span>
                            </li>
                            <li>
                                <a href="mailto:info@example.com">
                                    blossombox@gmail.com
                                </a>
                            </li>
                            <li>
                                <a href="tel:626997-4298">(088)521-4298</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Footer Link End -->
            </div>
            <div class="footer-col-3">
                    <!-- Footer Newsletter Start -->
                    <div class="footer-newsletter">
                        <h2 class="footer-title">Stay with us</h2>

                        <div class="footer-newsletter__form">
                            <p>
                                Follow us our social media
                            </p>

                            <ul class="footer-newsletter__social">
                                <li>
                                    <a href="#" aria-label="facebook">
                                        <i class="lastudioicon-b-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" aria-label="twitter">
                                        <i class="lastudioicon-b-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" aria-label="instagram">
                                        <i class="lastudioicon-b-instagram"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Footer Newsletter End -->
                </div>
        </div>
        <!-- Footer Main End -->

        <!-- Footer CopyRight Start -->
        <div class="footer-copyright">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="text-center text-md-start">
                        <p>
                            &copy;
                            <span class="current-year"></span>
                            <span> Blossom Box </span> | ITP4 
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center text-md-end">
                        <img src="assets/images/footer-payment-1.png" alt="Footer Payment" height="40" loading="lazy" />
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer CopyRight End -->
    </div>
</footer>
<?php 
if (isset($_SESSION['alert'])) {
    echo $_SESSION['alert'];
    unset($_SESSION['alert']);
}
?>

<!-- Footer End -->

<!-- JS Vendor, Plugins & Activation Script Files -->

<!-- Bootstrap JS -->
<script src="assets/js/bootstrap.bundle.min.js"></script>

<!-- Plugins JS -->
<script src="assets/js/swiper-bundle.min.js"></script>
<script src="assets/js/masonry.pkgd.min.js"></script>
<script src="assets/js/glightbox.min.js"></script>
<script src="assets/js/nice-select2.js"></script>

<!-- Activation JS -->
<script src="assets/js/main.js"></script>

</body>


<!-- Mirrored from htmldemo.net/lumin/lumin/login-register.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 19 May 2024 04:30:12 GMT -->
</html>
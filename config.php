<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;


// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

date_default_timezone_set('Asia/Manila');
$dateTime = new DateTime();

$timestamp = $dateTime->format('Y-m-d H:i:s');

session_start();
    $servername = $_ENV['DB_HOST'];
    $username = $_ENV['DB_USERNAME'];
    $password = $_ENV['DB_PASSWORD'];
    $dbname = $_ENV['DB_NAME'];

$conn = mysqli_connect($servername, $username, $password, $dbname);
$delivery_fee = 20.00;
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['logout'])) {
    session_destroy();
    session_unset();
    session_start();
    
    $_SESSION['alert'] = "<script>
    window.location.href = '/login-register.php';
</script>";
}
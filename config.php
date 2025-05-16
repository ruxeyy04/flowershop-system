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
    // Clear all session variables
    $_SESSION = array();
    
    // If a session cookie is used, destroy that too
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page without using session alert
    header("Location: /login-register.php");
    exit();
}
<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'lms_db';


$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
die('Database Connection Failed: ' . $conn->connect_error);
}
// set charset
$conn->set_charset('utf8mb4');


// start session globally
if (session_status() == PHP_SESSION_NONE) {
session_start();
}


function is_logged_in() {
return isset($_SESSION['user_id']);
}


function require_login() {
if (!is_logged_in()) {
header('Location: /lms/login.php');
exit;
}
}


function esc($s) {
return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>

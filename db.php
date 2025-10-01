<?php
$host = "localhost";
$user = "root";   // default in XAMPP
$pass = "";       // leave empty
$db   = "blog";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

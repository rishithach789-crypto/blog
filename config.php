<?php
$host = "localhost";
$user = "root";   // your MySQL username
$pass = "";       // your MySQL password
$db   = "task";   // your database name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
session_start();
include 'config.php';

// Only logged-in users can delete
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])){
    die("❌ No post ID provided.");
}
$id = (int)$_GET['id'];

// Delete
$stmt = $conn->prepare("DELETE FROM posts WHERE id=?");
$stmt->bind_param("i", $id);
if($stmt->execute()){
    header("Location: index.php");
    exit;
} else {
    echo "❌ Error: " . $stmt->error;
}
$stmt->close();
?>

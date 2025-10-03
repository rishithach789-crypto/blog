<?php
session_start();
include 'config.php';

if(!isset($_SESSION['username']) || $_SESSION['role']!='admin'){
    die("Access denied: Only admins can delete posts.");
}
if(!isset($_GET['id'])) die("No ID");

$id=(int)$_GET['id'];
$stmt=$conn->prepare("DELETE FROM posts WHERE id=?");
$stmt->bind_param("i",$id);
if($stmt->execute()){
    header("Location: index.php");exit;
} else echo "Error: ".$stmt->error;
?>

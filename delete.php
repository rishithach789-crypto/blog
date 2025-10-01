<?php
include 'config.php';

// Check if ID is provided
if(!isset($_GET['id']) || empty($_GET['id'])){
    die("❌ No post ID provided. Go back to <a href='index.php'>index</a>.");
}

$id = (int)$_GET['id'];

// Delete post
$sql = "DELETE FROM posts WHERE id=$id";

if(mysqli_query($conn, $sql)){
    echo "✅ Post deleted successfully! <a href='index.php'>Go back</a>";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}
?>

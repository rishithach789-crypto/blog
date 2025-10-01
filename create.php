<?php
include 'config.php';

// When form is submitted
if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "INSERT INTO posts (title, content) VALUES ('$title', '$content')";
    if(mysqli_query($conn, $sql)){
        echo "✅ Post added successfully! <a href='index.php'>Go back</a>";
    } else {
        echo "❌ Error: " . mysqli_error($conn);
    }
}
?>

<h1>Add New Post</h1>
<form method="POST">
  <input type="text" name="title" placeholder="Post Title" required><br><br>
  <textarea name="content" placeholder="Post Content" required></textarea><br><br>
  <button type="submit" name="submit">Submit</button>
</form>
<a href="index.php">Back to posts</a>

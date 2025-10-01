<?php
include 'config.php';

// Check if ID is provided
if(!isset($_GET['id']) || empty($_GET['id'])){
    die("❌ No post ID provided. Go back to <a href='index.php'>index</a>.");
}

$id = (int)$_GET['id']; // force it to be number

// Fetch post details
$result = mysqli_query($conn, "SELECT * FROM posts WHERE id=$id");
$post = mysqli_fetch_assoc($result);

if(!$post){
    die("❌ Post not found. Go back to <a href='index.php'>index</a>.");
}

// Update logic
if(isset($_POST['update'])){
    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "UPDATE posts SET title='$title', content='$content' WHERE id=$id";
    if(mysqli_query($conn, $sql)){
        echo "✅ Post updated successfully! <a href='index.php'>Go back</a>";
    } else {
        echo "❌ Error: " . mysqli_error($conn);
    }
}
?>

<h1>Edit Post</h1>
<form method="POST">
  <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required><br><br>
  <textarea name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea><br><br>
  <button type="submit" name="update">Update</button>
</form>
<a href="index.php">Back to posts</a>

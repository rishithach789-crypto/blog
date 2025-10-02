<?php
session_start();
include 'config.php';

// Only logged-in users can edit
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])){
    die("❌ No post ID provided.");
}
$id = (int)$_GET['id'];

// Fetch post
$stmt = $conn->prepare("SELECT * FROM posts WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$post){
    die("❌ Post not found.");
}

// Update
if(isset($_POST['update'])){
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("UPDATE posts SET title=?, content=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $content, $id);
    if($stmt->execute()){
        header("Location: index.php");
        exit;
    } else {
        $error = "❌ Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Post</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5 p-4 bg-white rounded shadow-sm">

  <h2>Edit Post</h2>
  <?php if(!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Title</label>
      <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Content</label>
      <textarea name="content" class="form-control" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea>
    </div>
    <button type="submit" name="update" class="btn btn-warning">Update</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
  </form>

</div>
</body>
</html>


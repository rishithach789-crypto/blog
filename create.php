<?php
session_start();
include 'config.php';

// Only logged-in users can add posts
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit;
}

if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $content);
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
  <title>Add Post</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5 p-4 bg-white rounded shadow-sm">

  <h2>Add New Post</h2>
  <?php if(!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Title</label>
      <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Content</label>
      <textarea name="content" class="form-control" rows="5" required></textarea>
    </div>
    <button type="submit" name="submit" class="btn btn-success">Submit</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
  </form>

</div>
</body>
</html>

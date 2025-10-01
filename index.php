<?php
session_start();
include 'config.php';

// Fetch all posts
$result = mysqli_query($conn, "SELECT * FROM posts ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Blog</title>
</head>
<body>
    <h1>All Blog Posts</h1>

    <!-- Authentication Links -->
    <?php if(isset($_SESSION['username'])): ?>
        <p>Welcome, <b><?php echo $_SESSION['username']; ?></b> | 
        <a href="logout.php">Logout</a></p>
        <a href="create.php">➕ Add New Post</a>
    <?php else: ?>
        <p><a href="login.php">Login</a> | 
        <a href="register.php">Register</a></p>
    <?php endif; ?>
    <hr>

    <!-- Show Posts -->
    <?php if(mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
            <small>Posted on <?php echo $row['created_at']; ?></small><br>

            <!-- Show Edit/Delete only if logged in -->
            <?php if(isset($_SESSION['username'])): ?>
                <a href="edit.php?id=<?php echo $row['id']; ?>">✏️ Edit</a> | 
                <a href="delete.php?id=<?php echo $row['id']; ?>" 
                   onclick="return confirm('Are you sure you want to delete this post?');">🗑️ Delete</a>
            <?php endif; ?>
            <hr>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No posts available.</p>
    <?php endif; ?>

</body>
</html>

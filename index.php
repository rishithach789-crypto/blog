<?php
session_start();
include 'config.php';

// Pagination
$limit = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$start = ($page - 1) * $limit;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$totalPosts = 0;

// Count posts
if ($search !== '') {
    $like = "%$search%";
    $countStmt = $conn->prepare("SELECT COUNT(*) FROM posts WHERE title LIKE ? OR content LIKE ?");
    $countStmt->bind_param("ss", $like, $like);
    $countStmt->execute();
    $countStmt->bind_result($totalPosts);
    $countStmt->fetch();
    $countStmt->close();

    $stmt = $conn->prepare("SELECT id,title,content,created_at FROM posts 
                            WHERE title LIKE ? OR content LIKE ? 
                            ORDER BY created_at DESC LIMIT ?,?");
    $stmt->bind_param("ssii", $like, $like, $start, $limit);
} else {
    $res = $conn->query("SELECT COUNT(*) AS total FROM posts");
    $row = $res->fetch_assoc();
    $totalPosts = $row['total'];

    $stmt = $conn->prepare("SELECT id,title,content,created_at FROM posts 
                            ORDER BY created_at DESC LIMIT ?,?");
    $stmt->bind_param("ii", $start, $limit);
}

$stmt->execute();
$result = $stmt->get_result();
$totalPages = ($totalPosts > 0) ? ceil($totalPosts / $limit) : 1;
?>
<!DOCTYPE html>
<html>
<head>
  <title>My Blog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-4 p-4 bg-white rounded shadow-sm">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>My Blog</h2>
    <div>
      <?php if(isset($_SESSION['username'])): ?>
        <span>Welcome, <b><?php echo $_SESSION['username']; ?></b> (Role: <?php echo $_SESSION['role']; ?>)</span>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
        <?php if($_SESSION['role'] != 'user'): ?>
          <a href="create.php" class="btn btn-success btn-sm">➕ Add Post</a>
        <?php endif; ?>
      <?php else: ?>
        <a href="login.php" class="btn btn-primary btn-sm">Login</a>
        <a href="register.php" class="btn btn-secondary btn-sm">Register</a>
      <?php endif; ?>
    </div>
  </div>

  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-9"><input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>"></div>
    <div class="col-md-3"><button class="btn btn-outline-primary w-100">Search</button></div>
  </form>

  <hr>

  <?php if($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="card mb-3">
        <div class="card-body">
          <h5><?php echo htmlspecialchars($row['title']); ?></h5>
          <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
          <small class="text-muted">Posted on <?php echo $row['created_at']; ?></small><br>
          <?php if(isset($_SESSION['role']) && $_SESSION['role'] != 'user'): ?>
            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-warning">Edit</a>
          <?php endif; ?>
          <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this post?');">Delete</a>
          <?php endif; ?>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No posts found.</p>
  <?php endif; ?>

  <!-- Pagination -->
  <nav>
    <ul class="pagination justify-content-center">
      <?php for($i=1;$i<=$totalPages;$i++): ?>
        <li class="page-item <?php if($i==$page) echo 'active'; ?>">
          <a class="page-link" href="?page=<?php echo $i; ?><?php echo $search ? '&search='.urlencode($search):''; ?>"><?php echo $i; ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>

</div>
</body>
</html>

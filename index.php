<?php
session_start();
include 'config.php';

// ---------------- SETTINGS ----------------
$limit = 5; // posts per page
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$start = ($page - 1) * $limit;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$totalPosts = 0;

// ---------------- COUNT TOTAL ----------------
if ($search !== '') {
    $like = "%$search%";
    $countStmt = $conn->prepare("SELECT COUNT(*) FROM posts WHERE title LIKE ? OR content LIKE ?");
    $countStmt->bind_param("ss", $like, $like);
    $countStmt->execute();
    $countStmt->bind_result($totalPosts);
    $countStmt->fetch();
    $countStmt->close();
} else {
    $res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM posts");
    $row = mysqli_fetch_assoc($res);
    $totalPosts = $row['total'];
}

$totalPages = ($totalPosts > 0) ? ceil($totalPosts / $limit) : 1;

// ---------------- FETCH POSTS ----------------
if ($search !== '') {
    $stmt = $conn->prepare("SELECT id, title, content, created_at FROM posts 
                             WHERE title LIKE ? OR content LIKE ?
                             ORDER BY created_at DESC LIMIT ?, ?");
    $stmt->bind_param("ssii", $like, $like, $start, $limit);
} else {
    $stmt = $conn->prepare("SELECT id, title, content, created_at FROM posts 
                             ORDER BY created_at DESC LIMIT ?, ?");
    $stmt->bind_param("ii", $start, $limit);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Blog</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-4 p-4 bg-white rounded shadow-sm">

  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">My Blog</h1>
    <div>
      <?php if(isset($_SESSION['username'])): ?>
        <span class="me-3">👋 Welcome, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b></span>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
        <a href="create.php" class="btn btn-success btn-sm">➕ Add Post</a>
      <?php else: ?>
        <a href="login.php" class="btn btn-primary btn-sm">Login</a>
        <a href="register.php" class="btn btn-secondary btn-sm">Register</a>
      <?php endif; ?>
    </div>
  </div>

  <!-- Search -->
  <form method="GET" action="index.php" class="row g-2 mb-3">
    <div class="col-md-9">
      <input type="text" name="search" class="form-control" 
             placeholder="Search posts..." 
             value="<?php echo htmlspecialchars($search); ?>">
    </div>
    <div class="col-md-3">
      <button type="submit" class="btn btn-outline-primary w-100">🔍 Search</button>
    </div>
  </form>

  <hr>

  <!-- Posts -->
  <?php if($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="card mb-3">
        <div class="card-body">
          <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
          <p class="card-text"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
          <p class="text-muted small">Posted on <?php echo $row['created_at']; ?></p>
          <?php if(isset($_SESSION['username'])): ?>
            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-warning">✏️ Edit</a>
            <a href="delete.php?id=<?php echo $row['id']; ?>" 
               onclick="return confirm('Are you sure you want to delete this post?');"
               class="btn btn-sm btn-outline-danger">🗑 Delete</a>
          <?php endif; ?>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p class="text-muted">No posts found<?php echo $search ? " for '".htmlspecialchars($search)."'" : ""; ?>.</p>
  <?php endif; ?>

  <!-- Pagination -->
  <nav>
    <ul class="pagination justify-content-center">
      <?php if($page > 1): ?>
        <li class="page-item">
          <a class="page-link" href="index.php?page=<?php echo $page-1; ?><?php echo $search ? '&search='.urlencode($search) : ''; ?>">⬅ Prev</a>
        </li>
      <?php endif; ?>

      <?php for($i=1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?php echo ($i==$page) ? 'active' : ''; ?>">
          <a class="page-link" href="index.php?page=<?php echo $i; ?><?php echo $search ? '&search='.urlencode($search) : ''; ?>">
            <?php echo $i; ?>
          </a>
        </li>
      <?php endfor; ?>

      <?php if($page < $totalPages): ?>
        <li class="page-item">
          <a class="page-link" href="index.php?page=<?php echo $page+1; ?><?php echo $search ? '&search='.urlencode($search) : ''; ?>">Next ➡</a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>

</div>
</body>
</html>

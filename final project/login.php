<?php
session_start();
include 'config.php';

if(isset($_POST['login'])){
    $username=$_POST['username'];
    $password=$_POST['password'];

    $stmt=$conn->prepare("SELECT id,username,password,role FROM users WHERE username=?");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $result=$stmt->get_result();
    $user=$result->fetch_assoc();

    if($user && password_verify($password,$user['password'])){
        $_SESSION['username']=$user['username'];
        $_SESSION['role']=$user['role'];
        header("Location: index.php");exit;
    } else {
        $error="Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5 p-4 bg-white rounded shadow-sm">
  <h2>Login</h2>
  <?php if(!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
  <form method="POST">
    <div class="mb-3"><label class="form-label">Username</label><input type="text" name="username" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
    <button type="submit" name="login" class="btn btn-primary">Login</button>
  </form>
  <a href="register.php">New user? Register</a>
</div>
</body>
</html>

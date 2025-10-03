<?php
include 'config.php';

if(isset($_POST['register'])){
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if(strlen($username) < 3){
        $error = "Username must be at least 3 characters.";
    } elseif(strlen($password) < 6){
        $error = "Password must be at least 6 characters.";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $check = $conn->prepare("SELECT id FROM users WHERE username=?");
        $check->bind_param("s",$username);
        $check->execute();
        $check->store_result();

        if($check->num_rows>0){
            $error="Username already exists!";
        } else {
            $stmt=$conn->prepare("INSERT INTO users (username,password,role) VALUES (?,?,'user')");
            $stmt->bind_param("ss",$username,$passwordHash);
            if($stmt->execute()){
                $success="Registration successful! <a href='login.php'>Login</a>";
            } else $error="Error: ".$stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5 p-4 bg-white rounded shadow-sm">
  <h2>Register</h2>
  <?php if(!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
  <?php if(!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
  <form method="POST">
    <div class="mb-3"><label class="form-label">Username</label><input type="text" name="username" class="form-control" required minlength="3"></div>
    <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required minlength="6"></div>
    <button type="submit" name="register" class="btn btn-primary">Register</button>
  </form>
  <a href="login.php">Already have account? Login</a>
</div>
</body>
</html>

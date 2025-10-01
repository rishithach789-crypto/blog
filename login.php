<?php
session_start();
include 'config.php';

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($result);

    if($user && password_verify($password, $user['password'])){
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
    } else {
        echo "❌ Invalid login!";
    }
}
?>

<h1>Login</h1>
<form method="POST">
  <input type="text" name="username" placeholder="Username" required><br><br>
  <input type="password" name="password" placeholder="Password" required><br><br>
  <button type="submit" name="login">Login</button>
</form>
<a href="register.php">New user? Register here</a>

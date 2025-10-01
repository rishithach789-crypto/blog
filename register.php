<?php
include 'config.php';

if(isset($_POST['register'])){
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $checkUser = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if(mysqli_num_rows($checkUser) > 0){
        echo "❌ Username already exists!";
    } else {
        mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
        echo "✅ Registration successful! <a href='login.php'>Login here</a>";
    }
}
?>

<h1>Register</h1>
<form method="POST">
  <input type="text" name="username" placeholder="Username" required><br><br>
  <input type="password" name="password" placeholder="Password" required><br><br>
  <button type="submit" name="register">Register</button>
</form>
<a href="login.php">Already have an account? Login</a>

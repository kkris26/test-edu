<?php
session_start();
require_once('config.php');
require_once('functions.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Implement additional security measures like hashing for password

    $loginSql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($loginSql);

    if ($result->num_rows > 0) {
        // User is authenticated, set session variables
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: index.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>

<body>
    <div class="login-form-container">
        <form action="login.php" method="post" class="login-form">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>
</body>

</html>
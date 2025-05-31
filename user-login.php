<?php
session_start();
include_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

   
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

     
        if (password_verify($password, $email['password'])) {
            $_SESSION['email_id'] = $email['id'];
            $_SESSION['email'] = $email['email'];
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<script>alert('Incorrect password!'); window.location.href='login.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('No user found with this email!'); window.location.href='login.php';</script>";
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log-in</title>
</head>
<body>
    <form action="dashboard.php" method="POST">
        <h3>Sign In</h3>
        <label for="email">Email</label><br>
        <input type="text" id="username" name="username"><br><br>
         <label for="pass">Password</label><br>
        <input type="pass" id="pass" name="pass"><br><br>
        <button type="submit">Sign In</button>
        <p>Don't have an account? <a href="user-register.php">Sign up</a></p>
    </form>
</body>
</html>
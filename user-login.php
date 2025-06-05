<?php
session_start();
include_once 'config.php';

$passwordErr = $emailErr = "";
$password = $email = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST["password"])){
        $passwordErr = "*Password is required";
    }else{
        $password = trim($_POST["password"]);
    }

    if(empty($_POST["email"])){
        $emailErr = "*Email is required";
    }else{
        $email = trim($_POST["email"]);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $emailErr = "*Invalid email format";
        }
    }

    if(empty($passwordErr) && empty($emailErr)){
        $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows == 1){
            $stmt->bind_result($hashedPasswordFromDB);
            $stmt->fetch();

            if(password_verify($password, $hashedPasswordFromDB)){
                $_SESSION["email"] = $email;
                header("Location: dashboard.php");
                exit;
            }else {
                $passwordErr = "*Incorrect password.";
            }
        }else {
            $emailErr = "*No account found with that email.";
        }
    $stmt->close();
    }
$conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/finals/styles/reg-log-style.css">
    <title>Log-in</title>
</head>
<body>
    <div class="form-container">
        <form action="user-login.php" method="POST">
            <h3>Sign in to your account.</h3>

            <div class="container-box">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <span class="error"><?php echo $emailErr; ?></span>
            </div>

            <div class="container-box">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <span class="error"><?php echo $passwordErr; ?></span>
            </div>

            <button type="submit">Sign In</button><br>
            <p class="text">Don't have an account? <a href="user-register.php">Sign up</a></p>
        </form>
    </div>
</body>
</html>

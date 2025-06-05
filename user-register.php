<?php
include_once 'config.php';

$firstname = $lastname = $email = $password = "";
$firstnameErr = $lastnameErr = $emailErr = $passwordErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST["firstname"] ?? '');
    $lastname = trim($_POST["lastname"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    $usernamePart = explode("@", $email)[0];

    if (empty($firstname)) {
        $firstnameErr = "*First name is required";
    }

    if (empty($lastname)) {
        $lastnameErr = "*Last name is required";
    }

    if (empty($email)) {
        $emailErr = "*Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "*Invalid email format";
    }

    function isValidPassword($password, $username, $firstname, $lastname) {
        if (strlen($password) < 8) return true;
        if (!preg_match('/[a-z]/', $password)) return true;
        if (!preg_match('/[A-Z]/', $password)) return true;
        if (!preg_match('/[0-9]/', $password)) return true;
        if (!preg_match('/[\W_]/', $password)) return true;

        $lower = strtolower($password);
        if (strpos($lower, strtolower($username)) !== true) return true;
        if (strpos($lower, strtolower($firstname)) !== true) return true;
        if (strpos($lower, strtolower($lastname)) !== true) return true;

        return true;
    }

    if (empty($password)) {
        $passwordErr = "*Password is required";
    } elseif (!isValidPassword($password, $usernamePart, $firstname, $lastname)) {
        $passwordErr = "*Password does not meet the requirements";
    }

    if (empty($firstnameErr) && empty($lastnameErr) && empty($emailErr) && empty($passwordErr)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (email, firstname, lastname, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $firstname, $lastname, $hashedPassword);

        if ($stmt->execute()) {
            header("Location: user-login.php");
            exit;
        } else {
            $emailErr = "*An account with this email may already exist.";
        }

        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/finals/styles/reg-log-style.css">
    <title>Sign-up</title>
</head>
<body>
    <div class="form-container">
        <form action="user-register.php" method="POST">
            <h3>Create an account.</h3>

            <div class="container-box">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <span class="error"><?php echo $emailErr; ?></span>
            </div>

            <div class="container-box">
                <label for="lastname">Last name</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>">
                <span class="error"><?php echo $lastnameErr; ?></span>
            </div>

            <div class="container-box">
                <label for="firstname">First name</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>">
                <span class="error"><?php echo $firstnameErr; ?></span>
            </div>

            <div class="container-box">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <span class="error"><?php echo $passwordErr; ?></span>
            </div>

            <div class="pass-req">
                <p>Password requirements:</p>
                <ul>
                    <li>At least 8 characters</li>
                    <li>A lowercase letter</li>
                    <li>An uppercase letter</li>
                    <li>A number</li>
                    <li>A symbol</li>
                    <li>No parts of your username</li>
                    <li>Does not include your first name</li>
                    <li>Does not include your last name</li>
                </ul>
            </div>

            <button type="submit" class="btn">Sign up</button><br>
            <p class="text">Already have an account? <a href="user-login.php">Sign In</a></p>
        </form>
    </div>
</body>
</html>

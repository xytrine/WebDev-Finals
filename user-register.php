<?php   
include_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $password = $_POST["password"];



    if (!empty($firstname) && !empty($lastname) && !empty($password) && filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $hashedpassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (email, firstname, lastname, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstname, $lastname, $email, $hashedpassword);

        if ($stmt->execute()) {
            header("location: user-login.php");
            echo "Registration successfu!";
    } else {
        echo "Error" . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid input. Please try again.";
}
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-up</title>
</head>
<body>
    <form action="user-register.php" method="POST">
        <h3>Sign up</h3>
        <label for="email">Email</label><br>
        <input type="text" id="email" name="email"><br><br>
          <label for="lastname">Last name</label><br>
        <input type="text" id="lastname" name="lastname"><br><br>
          <label for="firstname">First name</label><br>
        <input type="text" id="firstname" name="firstname"><br><br>
          <label for="password">Password</label><br>
        <input type="password" id="password" name="password">

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
    <p>Already have an account? <a href="user-login.php">Sign In</a></p>
    </form>
    
</body>
</html>
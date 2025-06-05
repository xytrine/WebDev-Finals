<?php
session_start();
include_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: user-login.php");
    exit;
}

if (!isset($_SESSION['email'])) {
    header("Location: user-login.php");
    exit;
}

$bookingMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['logout'])) {
    $from = trim($_POST['from'] ?? '');
    $to = trim($_POST['to'] ?? '');
    $depart = $_POST['depart'] ?? '';
    $return = $_POST['return'] ?? '';
    $adults = $_POST['adults'] ?? 0;
    $children = $_POST['children'] ?? 0;
    $infants = $_POST['infants'] ?? 0;
    $userEmail = $_SESSION['email'];

    if (!empty($from) && !empty($to) && !empty($depart)) {
        $check = $conn->prepare("SELECT id FROM bookings WHERE user_email = ? AND origin = ? AND destination = ? AND depart_date = ?");
        $check->bind_param("ssss", $userEmail, $from, $to, $depart);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $bookingMessage = "You already have a booking for this route and date.";
        } else {
            $stmt = $conn->prepare("INSERT INTO bookings (user_email, origin, destination, depart_date, return_date, adults, children, infants) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $userEmail, $from, $to, $depart, $return, $adults, $children, $infants);
            if ($stmt->execute()) {
                $bookingMessage = "Flight booked successfully!";
            } else {
                $bookingMessage = "Failed to book flight. Try again.";
            }
            $stmt->close();
        }
        $check->close();
    } else {
        $bookingMessage = "Please fill in required fields.";
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/finals/styles/dashboard.css">
    <title>Book Flight</title>
</head>
<body>
    <header class="book-container">
        <h1>Hi, where do you like to go?</h1>
        <form action="book.php" method="POST">
            <button type="submit" id="logout" name="logout">Log out</button>
        </form>
        
        <h4>flight</h4>
    </header>
    
    <form action="book.php" method="POST">
        <?php if (!empty($bookingMessage)): ?>
    <p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($bookingMessage); ?></p>
<?php endif; ?>
        <div class="col">
            <label for="from">From</label>
            <input type="text" id="from" name="from" placeholder="Select Origin">
        </div>

        <div class="col">
            <label for="to">To</label>
            <input type="text" id="to" name="to" placeholder="Select Destination">
        </div>

        <div class="col">
            <label for="depart">Depart</label>
            <input type="date" id="depart" name="depart">
        </div>

        <div class="col">
            <label for="return">Return</label>
            <input type="date" id="return" name="return">
        </div>

        <div class="col1">
            <label for="adults">Adults</label>
            <input type="number" id="adults" name="adults" min="0" placeholder="0">
            <p>12+ years</p>
        </div>

        <div class="col1">
            <label for="children">Children</label>
            <input type="number" id="children" name="children" min="0" placeholder="0">
            <p>2-11 years</p>
        </div>

        <div class="col1">
            <label for="infants">Infants</label>
            <input type="number" id="infants" name="infants" min="0" placeholder="0">
            <p>Under 2 years</p>
        </div>

        <button type="submit" class="btn">Book Flight</button>
    </form>
</body>
</html>

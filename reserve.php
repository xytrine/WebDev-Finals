<?php
session_start();
include_once 'config.php';

if (!isset($_SESSION['email'])) {
    header("Location: user-login.php");
    exit;
}

$bookingMessage = "";
$messageColor = "red"; 

$destinations = [
    'Cebu', 'Davao', 'Caticlan (Boracay)', 'Coron (Busuanga)', 'El Nido',
    'Siargao', 'Basco (Batanes)', 'Iloilo', 'Tacloban',
    'General Santos', 'Zamboanga', 'Puerto Princesa'
];

$destination = '';
$depart_date = '';
$return_date = '';
$adults = 1;
$children = 0;
$infants = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = $_SESSION['email'];
    $origin = 'Clark (CRK)';

    $destination = $_POST['destination'] ?? '';
    $depart_date = $_POST['depart_date'] ?? '';
    $return_date = $_POST['return_date'] ?? '';
    $adults = isset($_POST['adults']) ? intval($_POST['adults']) : 1;
    $children = isset($_POST['children']) ? intval($_POST['children']) : 0;
    $infants = isset($_POST['infants']) ? intval($_POST['infants']) : 0;


    if (!$destination) {
        $bookingMessage = "Please select a destination.";
    } elseif (!in_array($destination, $destinations)) {
        $bookingMessage = "Invalid destination selected.";
    } elseif (!$depart_date) {
        $bookingMessage = "Please select a departure date.";
    } elseif (!$return_date) {
        $bookingMessage = "Please select a return date.";
    } elseif ($adults < 1) {
        $bookingMessage = "At least one adult must be included.";
    } else {


        $stmt = $conn->prepare("INSERT INTO reservations (user_email, origin, destination, depart_date, return_date, adults, children, infants) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssiis", $user_email, $origin, $destination, $depart_date, $return_date, $adults, $children, $infants);

        if ($stmt->execute()) {
            $bookingMessage = "Flight reserved successfully!";
            $messageColor = "green";

            $destination = $depart_date = $return_date = '';
            $adults = 1; $children = 0; $infants = 0;
        } else {
            $bookingMessage = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Flight Reservation</title>
</head>
<body>
    <h2>Reserve Your Flight</h2>

    <?php if (!empty($bookingMessage)): ?>
        <p style="color: <?php echo $messageColor; ?>; font-weight: bold;">
            <?php echo htmlspecialchars($bookingMessage); ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="reserve.php">
        <label for="origin">From:</label>
        <select name="origin" id="origin" disabled>
            <option value="Clark (CRK)">Clark (CRK)</option>
        </select>
        <br><br>

        <label for="destination">To:</label>
        <select name="destination" id="destination" required>
            <option value="">--Select Destination--</option>
            <?php foreach ($destinations as $dest): ?>
                <option value="<?php echo htmlspecialchars($dest); ?>" <?php if ($dest === $destination) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($dest); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="depart_date">Departure Date:</label>
        <input type="date" name="depart_date" id="depart_date" value="<?php echo htmlspecialchars($depart_date); ?>" required>
        <br><br>

        <label for="return_date">Return Date:</label>
        <input type="date" name="return_date" id="return_date" value="<?php echo htmlspecialchars($return_date); ?>" required>
        <br><br>

        <label for="adults">Adults:</label>
        <input type="number" name="adults" id="adults" min="1" value="<?php echo htmlspecialchars($adults); ?>" required>
        <br><br>

        <label for="children">Children:</label>
        <input type="number" name="children" id="children" min="0" value="<?php echo htmlspecialchars($children); ?>">
        <br><br>

        <label for="infants">Infants:</label>
        <input type="number" name="infants" id="infants" min="0" value="<?php echo htmlspecialchars($infants); ?>">
        <br><br>

        <button type="submit">Reserve</button>
    </form>

    <form action="landing-page.php" method="post" style="margin-top:20px;">
        <button type="submit">Return Home</button>
    </form>
</body>
</html>

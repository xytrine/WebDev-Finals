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
$messageColor = "red"; 

// Lance Change: the from drop down is fixed as CRK(Clark) since dun galing naman talaga, the only modifiable part here is the destination
// user cant leave the departure and return dates as blank as its required by the booking 
$destinations = [
    'Cebu', 'Davao', 'Caticlan (Boracay)', 'Coron (Busuanga)', 'El Nido',
    'Siargao', 'Basco (Batanes)', 'Iloilo', 'Tacloban',
    'General Santos', 'Zamboanga', 'Puerto Princesa'
];

$from = 'Clark (CRK)';
$to = '';
$depart = '';
$return = '';
$adults = 1;
$children = 0;
$infants = 0;

if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['logout'])) {
    $from = trim($_POST['from'] ?? '');
    $from = 'Clark (CRK)'; 
    $to = trim($_POST['to'] ?? '');
    $depart = $_POST['depart'] ?? '';
    $return = $_POST['return'] ?? '';
    $adults = $_POST['adults'] ?? 0;
    $children = $_POST['children'] ?? 0;
    $infants = $_POST['infants'] ?? 0;
    $return = $_POST['return'] ?? null;
    $adults = isset($_POST['adults']) ? intval($_POST['adults']) : 0;
    $children = isset($_POST['children']) ? intval($_POST['children']) : 0;
    $infants = isset($_POST['infants']) ? intval($_POST['infants']) : 0;
    $userEmail = $_SESSION['email'];

    if (!empty($from) && !empty($to) && !empty($depart)) {

    if (!$to) {
        $bookingMessage = "Please select a destination.";
    } elseif (!in_array($to, $destinations)) {
        $bookingMessage = "Invalid destination selected.";
    } elseif (!$depart) {
        $bookingMessage = "Please select a departure date.";
    } elseif (!$return) {
        $bookingMessage = "Please select a return date.";
    } elseif ($adults < 1) {
        $bookingMessage = "At least one adult is required for booking.";
    } else {

        $check = $conn->prepare("SELECT id FROM bookings WHERE user_email = ? AND origin = ? AND destination = ? AND depart_date = ?");
        $check->bind_param("ssss", $userEmail, $from, $to, $depart);
        $check->execute();
@@ -36,82 +65,98 @@
            $bookingMessage = "You already have a booking for this route and date.";
        } else {
            $stmt = $conn->prepare("INSERT INTO bookings (user_email, origin, destination, depart_date, return_date, adults, children, infants) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $userEmail, $from, $to, $depart, $return, $adults, $children, $infants);
            $return_db = $return ?: null;
            $stmt->bind_param("ssssssii", $userEmail, $from, $to, $depart, $return_db, $adults, $children, $infants);

            if ($stmt->execute()) {
                $bookingMessage = "Flight booked successfully!";
                $messageColor = "green";

                $to = '';
                $depart = '';
                $return = '';
                $adults = 1;
                $children = 0;
                $infants = 0;
            } else {
                $bookingMessage = "Failed to book flight. Try again.";
                $bookingMessage = "Failed to book flight: " . $stmt->error;
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="/finals/styles/dashboard.css" />
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

    <h2>Book Your Flight</h2>

    <?php if (!empty($bookingMessage)): ?>
        <p style="color: <?php echo $messageColor; ?>; font-weight: bold;">
            <?php echo htmlspecialchars($bookingMessage); ?>
        </p>
    <?php endif; ?>

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
        <label for="from">From:</label>
        <select name="from" id="from" disabled>
            <option value="Clark (CRK)" selected>Clark (CRK)</option>
        </select>
        <br><br>

        <label for="to">To:</label>
        <select name="to" id="to" required>
            <option value="">-- Select Destination --</option>
            <?php foreach ($destinations as $dest): ?>
                <option value="<?php echo htmlspecialchars($dest); ?>" <?php if ($to === $dest) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($dest); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="depart">Depart:</label>
        <input type="date" id="depart" name="depart" value="<?php echo htmlspecialchars($depart); ?>" required>
        <br><br>

        <label for="return">Return:</label>
        <input type="date" id="return" name="return" value="<?php echo htmlspecialchars($return ?? ''); ?>">
        <br><br>

        <label for="adults">Adults:</label>
        <input type="number" id="adults" name="adults" min="1" value="<?php echo htmlspecialchars($adults); ?>" required>
        <br><br>

        <label for="children">Children:</label>
        <input type="number" id="children" name="children" min="0" value="<?php echo htmlspecialchars($children); ?>">
        <br><br>

        <label for="infants">Infants:</label>
        <input type="number" id="infants" name="infants" min="0" value="<?php echo htmlspecialchars($infants); ?>">
        <br><br>

        <button type="submit">Book Flight</button>
    </form>
    <br>

    <form action="landing-page.php" method="POST" style="display:inline;">
        <button type="submit">Return home</button>
    </form>


</body>
</html>
Footer

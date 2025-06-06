<?php
session_start();
include_once 'config.php';

if (!isset($_SESSION['email'])) {
    header("Location: user-login.php");
    exit;
}

$userEmail = $_SESSION['email'];

$userQuery = $conn->prepare("SELECT firstname FROM users WHERE email = ?");
$userQuery->bind_param("s", $userEmail);
$userQuery->execute();
$userResult = $userQuery->get_result();

$usersfirstname = "User"; 
if ($userRow = $userResult->fetch_assoc()) {
    $usersfirstname = $userRow['firstname'];
}
$userQuery->close();

// fetch bookings to put on the table
$bookingsStmt = $conn->prepare("SELECT origin, destination, depart_date, return_date, adults, children, infants FROM bookings WHERE user_email = ?");
$bookingsStmt->bind_param("s", $userEmail);
$bookingsStmt->execute();
$bookingsResult = $bookingsStmt->get_result();

// fetch reservations to put on the table
$reservationsStmt = $conn->prepare("SELECT origin, destination, depart_date, return_date, adults, children, infants FROM reservations WHERE user_email = ?");
$reservationsStmt->bind_param("s", $userEmail);
$reservationsStmt->execute();
$reservationsResult = $reservationsStmt->get_result();
?>




<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
</head>
<body>
    <h1>Profile & Booking-Reservation History</h1>
    <p>Logged in as: <?php echo htmlspecialchars($usersfirstname); ?></p>


    <h2>Your Bookings</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>From</th>
            <th>To</th>
            <th>Depart Date</th>
            <th>Return Date</th>
            <th>Adults</th>
            <th>Children</th>
            <th>Infants</th>
        </tr>
        <?php if ($bookingsResult->num_rows > 0): ?>
            <?php while ($row = $bookingsResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['origin']); ?></td>
                    <td><?php echo htmlspecialchars($row['destination']); ?></td>
                    <td><?php echo htmlspecialchars($row['depart_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['return_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['adults']); ?></td>
                    <td><?php echo htmlspecialchars($row['children']); ?></td>
                    <td><?php echo htmlspecialchars($row['infants']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">No bookings found.</td></tr>
        <?php endif; ?>
    </table>




    <h2>Your Reservations</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>From</th>
            <th>To</th>
            <th>Depart Date</th>
            <th>Return Date</th>
            <th>Adults</th>
            <th>Children</th>
            <th>Infants</th>
        </tr>
        <?php if ($reservationsResult->num_rows > 0): ?>
            <?php while ($row = $reservationsResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['origin']); ?></td>
                    <td><?php echo htmlspecialchars($row['destination']); ?></td>
                    <td><?php echo htmlspecialchars($row['depart_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['return_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['adults']); ?></td>
                    <td><?php echo htmlspecialchars($row['children']); ?></td>
                    <td><?php echo htmlspecialchars($row['infants']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">No reservations found.</td></tr>
        <?php endif; ?>
    </table>


    <br>
    <form action="book.php" method="post">
        <button type="submit" name="go_to_booking">Book Another Flight</button>
    </form>
    
    <form action="reserve.php" method="post">
        <button type="submit">Reserve a Flight</button>
    </form>

    <form action="landing-page.php" method="post">
        <button type="submit">Return Home</button>
    </form>

    <form action="user-login.php" method="post">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>
</html>

<?php
$bookingsStmt->close();
$reservationsStmt->close();
$conn->close();
?>

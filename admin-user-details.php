<?php
session_start();
include_once 'config.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: user-login.php");
    exit;
}

$userEmail = $_GET['email'] ?? '';

if (!$userEmail) {
    echo "No user selected.";
    exit;
}

// L: admin booking delete logic 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['delete_booking_id'])) {
        $deleteId = intval($_POST['delete_booking_id']);
        $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->bind_param("i", $deleteId);
        $stmt->execute();
        $stmt->close();
    }

// L: admin reservation delete logic
    if (isset($_POST['delete_reservation_id'])) {
        $deleteId = intval($_POST['delete_reservation_id']);
        $stmt = $conn->prepare("DELETE FROM reservations WHERE id = ?");
        $stmt->bind_param("i", $deleteId);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin-user-details.php?email=" . urlencode($userEmail));
    exit;
}

// L: user's name
$nameStmt = $conn->prepare("SELECT firstname FROM users WHERE email = ?");
$nameStmt->bind_param("s", $userEmail);
$nameStmt->execute();
$nameResult = $nameStmt->get_result()->fetch_assoc();
$nameStmt->close();
$username = $nameResult['firstname'] ?? 'Unknown';

// L: get user's booking details
$bookStmt = $conn->prepare("SELECT * FROM bookings WHERE user_email = ?");
$bookStmt->bind_param("s", $userEmail);
$bookStmt->execute();
$bookings = $bookStmt->get_result();
$bookStmt->close();

// L: get user's reservation details
$resStmt = $conn->prepare("SELECT * FROM reservations WHERE user_email = ?");
$resStmt->bind_param("s", $userEmail);
$resStmt->execute();
$reservations = $resStmt->get_result();
$resStmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Details Indiv</title>
</head>
<body>
    <h1>Bookings & Reservations for <?php echo htmlspecialchars($username); ?> (<?php echo htmlspecialchars($userEmail); ?>)</h1>

    <a href="admin-dashboard.php">Return to Admin Dashboard</a>
    <br><br>

    <h2><?php echo htmlspecialchars($username); ?>'s Bookings</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>From</th><th>To</th><th>Depart</th><th>Return</th>
            <th>Adults</th><th>Children</th><th>Infants</th><th>Action</th>
        </tr>
        <?php if ($bookings->num_rows > 0): ?>
            <?php while ($row = $bookings->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['origin']) ?></td>
                    <td><?= htmlspecialchars($row['destination']) ?></td>
                    <td><?= htmlspecialchars($row['depart_date']) ?></td>
                    <td><?= htmlspecialchars($row['return_date']) ?></td>
                    <td><?= htmlspecialchars($row['adults']) ?></td>
                    <td><?= htmlspecialchars($row['children']) ?></td>
                    <td><?= htmlspecialchars($row['infants']) ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="delete_booking_id" value="<?= $row['id'] ?>">
                            <button type="submit" onclick="return confirm('Cancel this booking?');">Cancel</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8">No bookings found.</td></tr>
        <?php endif; ?>
    </table>

    <br><br>

    <h2><?php echo htmlspecialchars($username); ?>'s Reservations</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>From</th><th>To</th><th>Depart</th><th>Return</th>
            <th>Adults</th><th>Children</th><th>Infants</th><th>Action</th>
        </tr>
        <?php if ($reservations->num_rows > 0): ?>
            <?php while ($row = $reservations->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['origin']) ?></td>
                    <td><?= htmlspecialchars($row['destination']) ?></td>
                    <td><?= htmlspecialchars($row['depart_date']) ?></td>
                    <td><?= htmlspecialchars($row['return_date']) ?></td>
                    <td><?= htmlspecialchars($row['adults']) ?></td>
                    <td><?= htmlspecialchars($row['children']) ?></td>
                    <td><?= htmlspecialchars($row['infants']) ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="delete_reservation_id" value="<?= $row['id'] ?>">
                            <button type="submit" onclick="return confirm('Cancel this reservation?');">Cancel</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8">No reservations found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
Footer

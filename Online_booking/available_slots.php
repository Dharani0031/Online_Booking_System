<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'db.php';

$stmt = $conn->query("SELECT booking_date, COUNT(*) as available_seats FROM booking_slots WHERE status = 'pending' GROUP BY booking_date");
$available_slots = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Slots</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Available Slots</h2>
        <ul>
            <?php foreach ($available_slots as $slot): ?>
                <li>
                    Date: <?php echo htmlspecialchars($slot['booking_date']); ?> - Available Seats: <?php echo htmlspecialchars($slot['available_seats']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>

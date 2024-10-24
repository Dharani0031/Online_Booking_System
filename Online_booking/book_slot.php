<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_date = $_POST['booking_date'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $seat_number = $_POST['seat_number'];

    // Check if the slot is available
    $stmt = $conn->prepare("SELECT id FROM booking_slots WHERE booking_date = ? AND seat_number = ? AND status = 'pending'");
    $stmt->bind_param("si", $booking_date, $seat_number);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Book the slot
        $stmt = $conn->prepare("UPDATE booking_slots SET status = 'booked' WHERE booking_date = ? AND seat_number = ?");
        $stmt->bind_param("si", $booking_date, $seat_number);

        if ($stmt->execute()) {
            echo "<script>alert('Seat booked successfully!'); window.location.href = 'dashboard.php';</script>";
        } else {
            echo "<script>alert('Booking failed: " . $stmt->error . "');</script>";
        }
    } else {
        echo "<script>alert('Seat is not available or incorrect details. Please choose another date or seat.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book a Slot</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Book a Slot</h2>
        <!-- getting the user input --> 
        <form method="post" action="">
            <label for="booking_date">Booking Date:</label>
            <input type="date" id="booking_date" name="booking_date" required>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="seat_number">Seat Number:</label>
            <input type="number" id="seat_number" name="seat_number" required>
            <button type="submit">Book Now</button>
        </form>
    </div>
</body>
</html>

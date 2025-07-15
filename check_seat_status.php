<?php
include 'connection/conn.php';  // Ensure connection is included

// Get the seat number from the request
if (isset($_GET['seat_number'])) {
    $seat_number = $_GET['seat_number'];

    // Query to check if the seat is booked
    $query = "SELECT status FROM user_form WHERE seat_number = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $seat_number);
        $stmt->execute();
        $stmt->store_result();

        // If a row exists, check the status
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($status);
            $stmt->fetch();

            if ($status == 'Booked') {
                echo 'booked';  // Seat is already booked
            } else {
                echo 'available';  // Seat is available
            }
        } else {
            echo 'available';  // Seat is available if no row is returned
        }
    } else {
        echo "Error: Could not check seat status.";
    }
}
?>

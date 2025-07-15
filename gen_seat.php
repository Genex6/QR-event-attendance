<?php
include 'connection/conn.php';

// This should create new seat numbers and return them as an array.
$newSeats = [];
$lastSeatNumberQuery = "SELECT MAX(seat_number) FROM user";  // Assuming you have a `seats` table with a `seat_number` column.
$result = mysqli_query($conn, $lastSeatNumberQuery);
$row = mysqli_fetch_assoc($result);
$lastSeatNumber = $row['MAX(seat_number)'];

$newSeatsCount = 6;  // Number of new seats to generate.

for ($i = 1; $i <= $newSeatsCount; $i++) {
    $newSeats[] = $lastSeatNumber + $i;  // Generating new seat numbers based on the last seat number.
}

// Return the new seats as a JSON response
echo json_encode($newSeats);
?>

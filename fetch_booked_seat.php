<?php
include 'connection/conn.php'; // Include the database connection

// Query to fetch all booked seats
$query = "SELECT seat_number FROM seats WHERE status = 'booked'";

// Execute the query
$result = mysqli_query($conn, $query);

// Prepare an array to store the booked seat numbers
$bookedSeats = [];

// Fetch the results and store seat numbers in the array
while ($row = mysqli_fetch_assoc($result)) {
    $bookedSeats[] = $row['seat_number'];
}

// Return the booked seats as a JSON response
echo json_encode($bookedSeats);
?>

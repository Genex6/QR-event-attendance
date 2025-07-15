<?php
// Include database connection file (e.g., 'conn.php')
include 'connection/conn.php';  // Make sure this file contains your DB connection settings
require_once 'phpqrcode/qrlib.php';  // Include the PHP QR code library

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values
    $title = $_POST['title'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $seat_number = $_POST['seat_number'];
    $status = $_POST['status'];

    // Check for empty fields (additional server-side validation)
    if (empty($title) || empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($seat_number) || empty($status)) {
        echo "All fields are required!";
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit;
    }

    // Sanitize input data
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $firstname = htmlspecialchars($firstname);
    $lastname = htmlspecialchars($lastname);
    $phone = htmlspecialchars($phone);

    // Check if the email already exists in the database
    $checkEmailSql = "SELECT email FROM user_form WHERE email = ?";
    if ($stmt = $conn->prepare($checkEmailSql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email already exists
            echo "The email is already registered. Please use a different email.";
            exit;
        }
    } else {
        echo "Error: Could not prepare the query to check email.";
        exit;
    }

    // Check if the seat is already booked
    $checkSeatSql = "SELECT seat_number FROM user_form WHERE seat_number = ?";
    if ($stmt = $conn->prepare($checkSeatSql)) {
        $stmt->bind_param("i", $seat_number);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Seat already booked
            echo "The selected seat is already booked. Please choose another seat.";
            exit;
        }
    }

    // Prepare SQL statement to insert data into the database
    $sql = "INSERT INTO user_form (title, first_name, last_name, email, phone, seat_number, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Initialize prepared statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters to the SQL query
        $stmt->bind_param("sssssss", $title, $firstname, $lastname, $email, $phone, $seat_number, $status);

        if ($stmt->execute()) {
            // Data successfully inserted, generate the QR code
            $userInfo = " name $title $firstname $lastname\nemail $email\n seat number $seat_number\nWELCOME TO MY PARTY";
            $path = 'uploads/';  // Path to save the QR code image
            $qrcodeFile = $path . time() . rand(1000, 9999) . ".png";  // Unique file name for the QR code

            // Generate QR code containing the user info
            QRcode::png($userInfo, $qrcodeFile, 'H', 8, 8);

            // Display the QR code and the user data
            echo "<div class='invite'>";
            echo "<h2>Invitation Successful!</h2>";
            echo "<h3>QR Code for your invitation information:</h3>";
            echo "<img src='" . $qrcodeFile . "' alt='QR Code' />";
            echo "<div class='event'>";

            // Assuming you have a query that fetches the events
            $query = "SELECT * FROM event";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($fetch_event = mysqli_fetch_assoc($result)) {
                    echo "<h1>Event Information</h1>";
                    echo "<p>Event Name: <span>" . $fetch_event['name'] . "</span></p>";
                    echo "<p>Date: <span>" . $fetch_event['date'] . "</span></p>";
                    echo "<p>Time: <span>" . $fetch_event['time'] . "</span></p>";
                    echo "<p>Location: <span>" . $fetch_event['location'] . "</span></p>";
                }
            } else {
                echo "<p>No events found.</p>";
            }

            echo "</div>";
            echo "</div>";

        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: Could not prepare the SQL query.";
    }

    // Clean up old QR code files
    $files = glob($path . "*.png");
    foreach ($files as $file) {
        if (filemtime($file) < time() - 3600) {
            if (!unlink($file)) {
                echo "Error deleting old QR code file: $file";
            }
        }
    }

    // Close the database connection
    $conn->close();
}
?>
<link rel="stylesheet" href="index.css">

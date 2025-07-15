<?php
// Include the database connection file
include 'connection/conn.php';  
// Include the PHP QR code library
require_once 'phpqrcode/qrlib.php';  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Insert data into the database
    $query = "INSERT INTO user_form (first_name, last_name, phone, email) VALUES (?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($query)) {
        // Bind the parameters to the prepared statement
        $stmt->bind_param("ssss", $firstname, $lastname, $phone, $email);

        if ($stmt->execute()) {
            // Data successfully inserted, generate the QR code
            $userInfo = "Name: $firstname $lastname\nEmail: $email\nPhone: $phone"; // Add phone number to user info
            $path = 'uploads/';  // Path to save the QR code image
            $qrcodeFile = $path . time() . ".png";  // Unique file name for the QR code

            // Generate QR code containing the user info
            QRcode::png($firstname, $qrcodeFile, 'H', 10, 10);

            // Display the QR code and the user data
            echo "<h3>Signup Successful!</h3>";
            echo "<p>QR Code for your signup information:</p>";
            echo "<img src='" . $qrcodeFile . "' alt='QR Code' />";

            // Optionally, show user info
            echo "<p>User Info:</p>";
            echo "<p>Name: $firstname $lastname</p>";
            echo "<p>Email: $email</p>";
            echo "<p>Phone: $phone</p>";

        } else {
            echo "Error: " . $stmt->error;
        }
    }
} else {
    echo "Invalid request method.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Form</title>
</head>
<body>
    <h2>Signup Form</h2>
    <form action="demo.php" method="POST">
        <div>
            <label for="firstname">First Name</label>
            <input type="text" required id="firstname" name="firstname">
        </div>
        <div>
            <label for="lastname">Last Name</label>
            <input type="text" required id="lastname" name="lastname">
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" required id="email" name="email">
        </div>
        <div>
            <label for="phone">Phone Number</label>
            <input type="tel" required id="phone" name="phone">
        </div>
        <input type="submit" value="Signup">
    </form>
</body>
</html>

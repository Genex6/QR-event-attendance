<?php
include 'connection/conn.php';
require_once 'phpqrcode/qrlib.php';

$path = 'uploads/';  // Path to store the generated QR code
$qrcode = $path . time() . ".png";  // Unique file name for the QR code

// Define the URL to the image you want to link to (replace with your actual image URL)
$image_url = "https://www.google.com/imgres?q=beautiful%20google&imgurl=https%3A%2F%2Fwww.chromethemer.com%2Fbackgrounds%2Fgoogle%2Fimages%2Fbeautiful-morning-4k-google-background.jpg&imgrefurl=https%3A%2F%2Fwww.chromethemer.com%2Fbackgrounds%2Fgoogle%2Fbeautiful-morning-4k-google-background.html&docid=tdhgNqhJUAYsoM&tbnid=n-R02iDSBm_MgM&vet=12ahUKEwizgqyGjuCLAxWHUUEAHUFxNuMQM3oECG0QAA..i&w=640&h=360&hcb=2&ved=2ahUKEwizgqyGjuCLAxWHUUEAHUFxNuMQM3oECG0QAA";  // Make sure this URL is correct and accessible

// Generate the QR code that contains the URL to the image
QRcode::png($image_url, $qrcode, 'H', 40, 40);

// Display the generated QR code
echo "<img src='" . $qrcode . "' alt='QR Code' />";

// Display a project-related message
echo "<p>NIIT project</p>";
?>

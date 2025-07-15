<?php
include 'connection/conn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code</title>
    <link rel="stylesheet" href="index.css">
    <script src="/index.js"></script>
</head>
<body>
    <section>
        <div class="formdiv">
            <h1>Fill in information to get your invitation</h1>
            <form action="process.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <p id="message"></p>
                <div>
                    <label for="title">Title</label>
                    <select name="title" id="title" required>
                        <option value="Mr">Mr</option>
                        <option value="Mrs">Mrs</option>
                        <option value="Miss">Miss</option>
                        <option value="Chief">Chief</option>
                        <option value="Alhaji">Alhaji</option>
                    </select>
                </div>
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
                <input type="hidden" id="seat_number" name="seat_number" value="" required>
                <input type="hidden" id="status" name="status" value="Booked">

                <div class="sittingarr">
                    <p onclick="opensit()" class="seatbtn" id="seatbtn">Pick your seat</p>
                    <div class="sitimg" id="sitimg" style="display:none;">
                        <a href="javascript:void(0)" onclick="close_sit()" class="x">X</a>
                        <img src="seat/42105261-top-view-of-a-3d-rendering-conference-room-a-white-round-table-and-six-chairs-around-office-removebg-preview.png" alt="Seat Map" style="width: 300px;" usemap="#seat">
            
                        <div id="seatContainer"></div>
                        <div>
                            <p onclick="generateNewSeats()" id="generateBtn" class="generate-btn" >Generate More Seats</p>
                        </div>
                    </div>
                    <div id="messageBox">
                        <p class="message" id="messageText"></p>
                    </div>
                </div>

                <div class="input">
                    <input type="submit" value="Get Invitation">
                </div>
            </form>
        </div>

        <!-- Displaying Products -->
        <?php 
            // Assuming you have a query that fetches the products
            $query = "SELECT * FROM occassion_image"; 
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($fetch_product = mysqli_fetch_assoc($result)) {
        ?>
            <div class="product">
                <div class="img">
                    <img src="image/<?php echo htmlspecialchars($fetch_product['image']); ?>" alt="" style="height:600px;object-fit:contain;">
                </div>
                <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($fetch_product['image']); ?>">
            </div>
        <?php 
                }
            } else {
                echo "<p>No products found matching your criteria.</p>";
            }
        ?>
    </section>
</body>

<script>
// Seat generation and booking script
let totalSeats = 6; // Starting number of seats
let bookedSeats = 0; // Track booked seats
const maxSeats = 30; // Maximum number of seats allowed

// Function to open the seat selection
function opensit() {
    document.getElementById("sitimg").style.display = "block";
    document.getElementById("seatbtn").style.display = "none";
    generateSeats(); // Generate seats dynamically
}

// Function to close the seat selection
function close_sit() {
    document.getElementById("sitimg").style.display = "none";
    document.getElementById("seatbtn").style.display = "block";
    document.getElementById("messageBox").style.display = "none";
}

function generateSeats() {
    const seatContainer = document.getElementById("seatContainer");
    seatContainer.innerHTML = ''; // Clear old seat elements

    // Generate seats based on the available totalSeats
    for (let i = bookedSeats + 1; i <= totalSeats; i++) {
        const seat = document.createElement("span");
        seat.classList.add("seat", "empty");
        seat.innerText = ` ${i}`;
        seat.id = `seat${i}`;
        seat.onclick = () => bookSeat(seat, i);
        seatContainer.appendChild(seat);
    }

    // Disable the "Generate More Seats" button if max seats are reached
    if (totalSeats === maxSeats) {
        document.getElementById("generateBtn").disabled = true;
    }
}

// Function to generate new seats
function generateNewSeats() {
    if (totalSeats + 6 > maxSeats) {
        displayMessage("Maximum number of seats reached.");
        return;
    }

    const newSeatsCount = 6; // Number of new seats to add
    let newSeats = [];
    let startSeatNumber = totalSeats + 1;

    for (let i = 0; i < newSeatsCount; i++) {
        newSeats.push(startSeatNumber);
        startSeatNumber++;
    }

    totalSeats += newSeatsCount;

    displayMessage(`New seats have been added: ${newSeats.join(', ')}`);

    generateSeats(); // Regenerate the seat map

    if (totalSeats === maxSeats) {
        document.getElementById("generateBtn").disabled = true;
    }
}

// Function to book a selected seat
function bookSeat(seat, seatNumber) {
    if (seat.classList.contains('booked')) {
        displayMessage(`Seat ${seatNumber} is already booked.`);
        return;
    }

    seat.classList.add('booked');
    seat.classList.remove('empty');
    seat.innerText = ` ${seatNumber} (Booked)`;  // Update seat label

    // Store the selected seat number in a hidden input field
    document.getElementById("seat_number").value = seatNumber;

    bookedSeats++;  // Increase booked seat count

    // Disable all other seats after booking
    disableOtherSeats();

    document.getElementById("generateBtn").classList.add("disabled");
    document.getElementById("generateBtn").classList.add("disabled").style.cursor=" not-allowed";
            
document.getElementById("generateBtn").disabled = true;



    if (bookedSeats === totalSeats) {
        displayMessage("All seats in this batch are booked!");
        generateNewSeats();  // Generate new batch of seats if needed
    }
}

// Disable non-booked seats
function disableOtherSeats() {
    const allSeats = document.querySelectorAll('.seat');
    allSeats.forEach(seat => {
        if (!seat.classList.contains('booked')) {
            seat.classList.add('disabled');
            seat.style.cursor = 'not-allowed';
            seat.onclick = null;
        }
    });
}
function applyOverlay(seat) {
            seat.style.backgroundColor = "none";  // Indicate seat is booked
            seat.style.backgroundColor = "green";
            seat.style.color = "white";  // Change text color
            seat.style.cursor = "not-allowed";  // Disable further booking
        }
// Function to display messages
function displayMessage(message) {
    const messageBox = document.getElementById("messageBox");
    const messageText = document.getElementById("messageText");
    messageText.innerText = message;
    messageBox.style.display = "block";

    setTimeout(() => {
        messageBox.style.display = "none";
    }, 4000);
}

// Form validation function
function validateForm() {
    const firstname = document.getElementById("firstname").value;
    const lastname = document.getElementById("lastname").value;
    const phone = document.getElementById("phone").value;
    const email = document.getElementById("email").value;
    const seatNumber = document.getElementById("seat_number").value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 
    const messageElement = document.getElementById("message");

    messageElement.textContent = '';

    let errorMessage = [];

    if (firstname.trim() === "") {
        errorMessage.push("First name is required.");
    }

    if (lastname.trim() === "") {
        errorMessage.push("Last name is required.");
    }

    if (phone.trim() === "") {
        errorMessage.push("Phone number is required.");
    }

    if (!emailRegex.test(email)) {
        errorMessage.push("Please enter a valid email address.");
    }

    if (seatNumber === "") {
        errorMessage.push("Please select a seat.");
    }

    if (errorMessage.length > 0) {
        messageElement.textContent = errorMessage.join(" ");
        return false;
    }

    return true;
}
</script>
</html>

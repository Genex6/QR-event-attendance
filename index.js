function validateForm() {
    // Get form elements
    const firstname = document.getElementById("Firstname").value;
    const phone = document.getElementById("phone").value;
    const email = document.getElementById("email").value;
    const lastname = document.getElementById("lastname").value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;  // Email pattern

    // Validate lastName
    if (lastname.trim() === "") {
        alert("lastName is required.");
        return false; // Stop form submission
    }
    // Validate firstName
     if (firstname.trim() === "") {
        alert("firstName is required.");
        return false; // Stop form submission
    }
    // Validate phone
     if (phone.trim() === "") {
        alert("phone number is required.");
        return false; // Stop form submission
    }

    // Validate Email
    if (!emailRegex.test(email)) {
        alert("Please enter a valid email address.");
        return false; // Stop form submission
    }

    
    // If all validations pass
    alert("Form submitted successfully!");
    return true; // Allow form submission
}
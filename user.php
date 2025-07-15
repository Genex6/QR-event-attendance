<?php include 'inc/adminheader.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<section>
    
    <div class="boxord">
        <h1 class="title">All Users</h1>
        <?php 
        // Fetch all users
        $select_user = mysqli_query($conn, "SELECT * FROM `user_form`") or die('Query failed: ' . mysqli_error($conn));
        
        // Check if there are any users
        if (mysqli_num_rows($select_user) > 0) {
            while ($fetch_user = mysqli_fetch_assoc($select_user)) {
        ?>
     <!-- title, first_name, last_name, email, phone -->
        <div class="order-card">
            <p>User title: <span><?php echo $fetch_user['title']; ?></span></p>
            <p>user first Name: <span><?php echo htmlspecialchars($fetch_user["first_name"]); ?></span></p>
            <p>user last Name: <span><?php echo htmlspecialchars($fetch_user["last_name"]); ?></span></p>
            <p>user Email: <span><?php echo htmlspecialchars($fetch_user['email']); ?></span></p>
            <p>user Password: <span>******</span></p> <!-- Hidden password for security -->
            <p>User phone: <span><?php echo htmlspecialchars($fetch_user['phone']); ?></span></p>
            <p>User seat: <span><?php echo htmlspecialchars($fetch_user['seat_number']); ?></span></p>
            <p>seat status: <span><?php echo htmlspecialchars($fetch_user['status']); ?></span></p>

            <!-- Delete User Link -->
            <a href="user.php?delete=<?php echo $fetch_user['id']; ?>" class="delete" onclick="return confirm('Delete this user?');">Delete</a>
        </div>

        <?php 
            }
        } else {
            echo '<div class="boxempty"><p>No users found.</p></div>';
        }
        ?>
    </div>
</section>

<?php
// Handle delete request
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    // Use prepared statements to prevent SQL injection
    $delete_user = mysqli_prepare($conn, "DELETE FROM `user_form` WHERE id = ?");
    mysqli_stmt_bind_param($delete_user, "i", $delete_id); // "i" for integer
    if (mysqli_stmt_execute($delete_user)) {
        echo '<script>alert("User deleted successfully!"); window.location.href="user.php";</script>';
    } else {
        echo '<script>alert("Error deleting user: ' . mysqli_error($conn) . '"); window.location.href="user.php";</script>';
    }
}
?>
<?php include 'inc/footer.php'; ?>
</body>
</html>

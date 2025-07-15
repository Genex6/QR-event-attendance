<?php include 'inc/adminheader.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="dashborad">
   <section class="dashborad1">
        <div class="boxcont">
            <div class="box">
                <?php
                    // Ensure the database connection is valid
                    if (isset($conn)) {
                        $select_order = mysqli_query($conn, "SELECT * FROM `event`");
                        if (!$select_order) {
                            die('Query failed: ' . mysqli_error($conn));
                        }
                        $num_of_orders = mysqli_num_rows($select_order);
                    }
                ?>
                <h3>
                    <i class='bx bx-cart'></i><?php echo htmlspecialchars($num_of_orders); ?>
                </h3>
                <p>Events Placed</p>
            </div>

            <div class="box">
                <?php
                    if (isset($conn)) {
                        $select_product = mysqli_query($conn, "SELECT * FROM `occassion_image`");
                        if (!$select_product) {
                            die('Query failed: ' . mysqli_error($conn));
                        }
                        $num_of_products = mysqli_num_rows($select_product);
                    }
                ?>
                <h3>
                    <?php echo htmlspecialchars($num_of_products); ?>
                </h3>
                <p>Images Added</p>
            </div>

            <div class="box">
                <?php
                    if (isset($conn)) {
                        // Adjust the query as needed based on your actual table structure
                        $select_user = mysqli_query($conn, "SELECT status FROM `user_form`");
                        if (!$select_user) {
                            die('Query failed: ' . mysqli_error($conn));
                        }
                        $num_of_user = mysqli_num_rows($select_user);
                    }
                ?>
                <h3>
                    <i class='bx bxs-user'></i><?php echo htmlspecialchars($num_of_user); ?>
                </h3>
                <p>Total Seats Booked</p>
            </div>

            <div class="box">
                <?php
                    if (isset($conn)) {
                        $select_user = mysqli_query($conn, "SELECT * FROM `user_form`");
                        if (!$select_user) {
                            die('Query failed: ' . mysqli_error($conn));
                        }
                        $num_of_user = mysqli_num_rows($select_user);
                    }
                ?>
                <h3>
                    <i class='bx bxs-user'></i><?php echo htmlspecialchars($num_of_user); ?>
                </h3>
                <p>Total Number of Users</p>
            </div>
        </div>
   </section>
   <?php include 'inc/footer.php' ?>
</body>
</html>

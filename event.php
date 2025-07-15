<?php include 'inc/adminheader.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <?php include 'inc/footer.php'; ?>
</body>
</html>

<?php
include 'inc/adminheader.php';

// Handle the form submission and event upload
if (isset($_POST['add_product'])) {
    // Get event details from form
    $event_name = mysqli_real_escape_string($conn, $_POST['name']);
    $event_date = mysqli_real_escape_string($conn, $_POST['date']);
    $event_time = mysqli_real_escape_string($conn, $_POST['time']);
    $event_location = mysqli_real_escape_string($conn, $_POST['location']);
    
    // Insert the event into the database only after validating the data
    $insert_event = mysqli_query($conn, "INSERT INTO `event`(`name`, `time`, `location`, `date`) 
    VALUES ('$event_name', '$event_time', '$event_location', '$event_date')") or die('Query failed');
    
    $message[] = 'Event added successfully';
};

// Event Deletion Logic
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    // Delete the event from the database
    $delete_event = mysqli_query($conn, "DELETE FROM `event` WHERE id='$delete_id'") or die('Query failed');
  
    $message[] = 'Event deleted successfully';
    header('Location: event.php'); // Redirect to avoid resubmission of the delete action
    exit;
}

// Event Editing Logic
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    // Fetch the event details from the database
    $edit_event = mysqli_query($conn, "SELECT * FROM `event` WHERE id='$edit_id'") or die('Query failed');
    $event = mysqli_fetch_assoc($edit_event);
    
    // Handle the update of the event details
    if (isset($_POST['update_product'])) {
        $event_name = mysqli_real_escape_string($conn, $_POST['name']);
        $event_date = mysqli_real_escape_string($conn, $_POST['date']);
        $event_time = mysqli_real_escape_string($conn, $_POST['time']);
        $event_location = mysqli_real_escape_string($conn, $_POST['location']);
        
        // Update event in the database
        $update_event = mysqli_query($conn, "UPDATE `event` SET `name`='$event_name', `date`='$event_date', `time`='$event_time', `location`='$event_location' WHERE id='$edit_id'") or die('Query failed');
        
        $message[] = 'Event updated successfully';
        header('Location: event.php'); // Redirect to avoid resubmission of the update action
        exit;
    }
}
?>

<!-- HTML for the event upload form -->
<link rel="stylesheet" href="admin.css">
<section class="add-product">
    <!-- Display messages (success or error) -->
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<div class="message">
            <span>'. $msg .'</span>
            <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
            </div>';
        }
    }
    ?>
   
     <h2>Add Event</h2>
    <!-- Event upload form -->
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Event name..." required>
        <input type="text" name="time" placeholder="Event time..." required>
        <input type="text" name="date" placeholder="Event date..." required>
        <textarea name="location" cols="30" rows="10" required placeholder="Event location..."></textarea>
        <input type="submit" value="Add Event" name="add_product" class="btnbut">
    </form>
</section>

<!-- Event Edit Form (only visible if editing an event) -->
<?php if (isset($_GET['edit'])): ?>
<section class="edit-product">
    <h2>Edit Event</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?edit=<?php echo $event['id']; ?>" method="post" enctype="multipart/form-data">
        <input type="text" name="name" value="<?php echo htmlspecialchars($event['name']); ?>" required>
        <input type="text" name="time" value="<?php echo htmlspecialchars($event['time']); ?>" required>
        <input type="text" name="date" value="<?php echo htmlspecialchars($event['date']); ?>" required>
        <textarea name="location" cols="30" rows="10" required><?php echo htmlspecialchars($event['location']); ?></textarea>
        <div class="opt">
            <input type="submit" value="Update Event" name="update_product" class="edit">
            <input type="reset" value="Cancel" class="option-btn" id="close-form" >
        </div>
    </form>
</section>
<div class="lin3"></div>
<?php endif; ?>

<!-- Display the list of events -->
<div class="line1"></div>
<div class="line2"></div>
<section class="showproduct">
    <div class="boxproduct">
        <?php
        // Fetch all events from the database
        $select_events = mysqli_query($conn, "SELECT * FROM `event`") or die('Query failed');
        if (mysqli_num_rows($select_events) > 0) {
            while ($fetch_event = mysqli_fetch_assoc($select_events)) {
        ?>
        <div class="boxp">
            <!-- Display event details -->
            <div class="boxptext" style="color: black;">
                <p>Name: <span> <?php echo $fetch_event['name']; ?></span></p>
                <p>Time: <span> <?php echo $fetch_event['time']; ?></span></p>
                <p>Date: <span> <?php echo $fetch_event['date']; ?></span></p>
                <p>location:<span class="detail"><?php echo $fetch_event['location']; ?></span></p>
                <!-- Edit and delete links -->
                <div class="mbt">
                    <a href="event.php?edit=<?php echo $fetch_event['id']; ?>" class="edit">Edit</a>
                    <a href="event.php?delete=<?php echo $fetch_event['id']; ?>" class="delete" onclick="return confirm('Delete this event?');">Delete</a>
                </div>
            </div>
        </div>
        <?php    
            }
        } else {
            echo '<div class="boxempty">
                    <p>No events added yet</p>
                  </div>';
        }
        ?>
    </div>
</section>

<?php include 'inc/footer.php' ?>
<script>
    let closeBtn = document.querySelector('#close-form');
    closeBtn.addEventListener('click', () => {
        document.querySelector('.edit-product').style.display = 'none';
    });
</script>

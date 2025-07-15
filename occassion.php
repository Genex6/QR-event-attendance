<?php include 'inc/adminheader.php'; 
// Handle the form submission and product upload
if (isset($_POST['add_product'])) {
    // Get product details from form
   
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'image/' . $image;
    
    // Check if the product name already exists in the database
    $select_product_name = mysqli_query($conn, "SELECT image FROM `occassion_image` WHERE image='$image'") or die('Query failed');
    if (mysqli_num_rows($select_product_name) > 0) {
        $message[] = 'Product name already exists';
    } else {
        // Check image size before uploading
        if ($image_size > 2000000) {
            $message[] = 'Image is too large, maximum size is 2MB';
        } else {
            // Insert the product into the database only after validating the image size
            $insert_product = mysqli_query($conn, "INSERT INTO `occassion_image`( `image`) VALUES ( '$image')") or die('Query failed');
            if ($insert_product) {
                // Move the uploaded image file to the image folder
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Product added successfully';
            } else {
                $message[] = 'Failed to upload product';
            }
        }
    }
};

// Product Deletion Logic
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    // Delete the product from the database
    $delete_product = mysqli_query($conn, "DELETE FROM `occassion_image` WHERE id='$delete_id'") or die('Query failed');
    // $delete_product_cart = mysqli_query($conn, "DELETE FROM `cart` WHERE pid='$delete_id'") or die('Query failed');
    
    $message[] = 'Product deleted successfully';
    header('Location: occassion.php'); // Redirect to avoid resubmission of the delete action
    exit;
}

// Product Editing Logic
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    // Fetch the product details from the database
    $edit_product = mysqli_query($conn, "SELECT * FROM `occassion_image` WHERE id='$edit_id'") or die('Query failed');
    $product = mysqli_fetch_assoc($edit_product);
    
    // Handle the update of the product details
    if (isset($_POST['update_product'])) {
       
        $updated_image = $_FILES['image']['name'];
        $updated_image_size = $_FILES['image']['size'];
        $updated_image_tmp_name = $_FILES['image']['tmp_name'];
        $updated_image_folder = 'image/' . $updated_image;

        // Handle image update or retain old image if no new image is uploaded
        if ($updated_image != "") {
            // Validate new image size
            if ($updated_image_size > 2000000) {
                $message[] = 'Image is too large, maximum size is 2MB';
            } else {
                move_uploaded_file($updated_image_tmp_name, $updated_image_folder);
                $image_to_update = $updated_image;
            }
        } else {
            $image_to_update = $product['image'];  // Keep existing image if none provided
        }

        // Update product in the database
        $update_product = mysqli_query($conn, "UPDATE `occassion_image` SET `id`='$edit_id', `image`='$image_to_update' WHERE id='$edit_id'") or die('Query failed');
        $message[] = 'Product updated successfully';
        header('Location: occassion.php'); // Redirect to avoid resubmission of the delete action
    exit;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
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
    
        <h2>Add occassion image</h2>
        <!-- Product upload form -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
           <input type="file" name="image" accept="image/jpg,image/png,image/jpeg,image/webp" required>
            <input type="submit" value="Add Product" name="add_product" class="btnbut">
        </form>
    </section>
    <!-- Product Edit Form (only visible if editing a product) -->
    <?php if (isset($_GET['edit'])): ?>
    <section class="edit-product">
        <h2>Edit occassion image</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?edit=<?php echo $product['id']; ?>" method="post" enctype="multipart/form-data">
            <input type="file" name="image" accept="image/jpg,image/png,image/jpeg,image/webp">
            <div class="opt">
                <input type="submit" value="Update Product" name="update_product" class="edit">
                <input type="reset" value="cancle" class="option-btn" id="close-form" >
            </div>
        
        </form>
        <?php 
            echo"<script>document.querySelector('.edit-product').style.display='block'</script>";    
        ?>
    </section>
    <div class="lin3"></div>
    <?php endif; ?>

    <!-- Display the list of products -->
    <div class="line1"></div>
    <div class="line2"></div>
    <section class="showproduct">
        <div class="boxproduct">
            <?php
            // Fetch all products from the database
            $select_products = mysqli_query($conn, "SELECT * FROM `occassion_image`") or die('Query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
            ?>
            <div class="boxp">
                <!-- Display product details -->
                <img src="image/<?php echo $fetch_products['image']; ?>" alt="Product Image">
                <div class="boxptext">
                    
                    <!-- Edit and delete links -->
                    <div class="mbt">
                        <a href="occassion.php?edit=<?php echo $fetch_products['id']; ?>" class="edit">Edit</a>
                        <a href="occassion.php?delete=<?php echo $fetch_products['id']; ?>" class="delete" onclick="return confirm('Delete this product?');">Delete</a>
            
                    </div>
                </div>
            </div>
            <?php    
                }
            } else {
                echo '<div class="boxempty">
                        <p>No products added yet</p>
                    </div>';
            }
            ?>
        </div>
    </section>

    <script>
        let closeBtn=document.querySelector('#close-form');
    closeBtn.addEventListener('click',()=>{
        document.querySelector('.edit-product').style.display='none'
    })
    </script>
    <?php include 'inc/footer.php'; ?>
</body>
</html>
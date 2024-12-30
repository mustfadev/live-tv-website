<?php 
include_once('includes/config.php'); // Include database configuration
include_once('includes/session_check.php'); // Include session check for user authentication
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Category</title>
    <link rel="stylesheet" href="assets/css/styles.min.css" /> <!-- Link to the main CSS stylesheet -->
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Edit Category</h2>
        
        <?php
        // Check if 'id' is passed in the URL
        if (isset($_GET['id'])) {
            $category_id = intval($_GET['id']); // Convert the ID to an integer for safety
            $stmt = $connect->prepare("SELECT * FROM tbl_category WHERE cid = ?"); // Prepare the SQL statement
            $stmt->bind_param("i", $category_id); // Bind the parameter
            $stmt->execute(); // Execute the statement
            $result = $stmt->get_result(); // Get the result set

            // Check if the category exists
            if ($result->num_rows > 0) {
                $category = $result->fetch_assoc(); // Fetch the category details
            } else {
                echo "<div class='alert alert-danger'>Category not found.</div>"; // Display error if category not found
                exit; // Exit script
            }
        } else {
            echo "<div class='alert alert-danger'>Invalid category ID.</div>"; // Display error if no ID was provided
            exit; // Exit script
        }
        ?>

        <form method="POST" action=""> <!-- Form to edit the category -->
            <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" 
                       value="<?php echo htmlspecialchars($category['category_name']); ?>" required> <!-- Pre-fill with existing name -->
            </div>
            <input type="hidden" name="category_id" value="<?php echo $category['cid']; ?>"> <!-- Hidden input for category ID -->
            <button type="submit" class="btn btn-primary">Update Category</button> <!-- Update button -->
            <a href="Categories.php" class="btn btn-secondary">Back to Categories</a> <!-- Back button -->
        </form>

        <?php
        // Process the form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $category_id = intval($_POST['category_id']); // Get and sanitize the category ID
            $category_name = trim($_POST['category_name']); // Get and sanitize the category name

            // Prepare the SQL statement to update the category
            $stmt = $connect->prepare("UPDATE tbl_category SET category_name = ? WHERE cid = ?");
            $stmt->bind_param("si", $category_name, $category_id); // Bind the parameters

            // Execute the update
            if ($stmt->execute()) {
                echo "<div class='alert alert-success mt-3'>Category updated successfully!</div>"; // Success message
                echo "<script>setTimeout(() => { window.location.href='Categories.php'; }, 2000);</script>"; // Redirect after 2 seconds
            } else {
                echo "<div class='alert alert-danger mt-3'>Error updating category. Please try again.</div>"; // Error message
            }

            $stmt->close(); // Close the statement
        }
        ?>
    </div>

    <script src="assets/libs/jquery/dist/jquery.min.js"></script> <!-- jQuery library -->
    <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JavaScript -->
</body>

</html>

<?php $connect->close(); ?> <!-- Close the database connection -->

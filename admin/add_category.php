<?php 
include_once('includes/config.php'); // Include configuration file
include_once('includes/session_check.php'); // Check session
include_once ('includes/texts.php'); // Include necessary texts
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Category</title>
    <link rel="stylesheet" href="assets/css/styles.min.css" />
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Add New Category</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" required placeholder="Enter category name">
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
            <a href="Categories.php" class="btn btn-secondary">Back to Categories</a>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Check request method
            $category_name = trim($_POST['category_name']); // Trim whitespace

            // Insert category into database
            $stmt = $connect->prepare("INSERT INTO tbl_category (category_name) VALUES (?)"); // Prepare the query
            $stmt->bind_param("s", $category_name); // Bind parameters

            if ($stmt->execute()) { // Execute the query
                echo "<div class='alert alert-success mt-3'>Category added successfully!</div>"; // Success message
                echo "<script>setTimeout(() => { window.location.href='Categories.php'; }, 2000);</script>"; // Redirect after 2 seconds
            } else {
                echo "<div class='alert alert-danger mt-3'>Error adding category. Please try again.</div>"; // Error message
            }

            $stmt->close(); // Close the statement
        }
        ?>
    </div>

    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php $connect->close(); // Close the database connection ?> 

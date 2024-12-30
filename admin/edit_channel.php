<?php
ob_start(); // Start output buffering
include_once('includes/config.php'); // Include database connection settings
include_once('includes/session_check.php'); // Include session validation

// Check if the channel ID is present in the URL
if (isset($_GET['id'])) {
    $channel_id = intval($_GET['id']); // Get the channel ID and ensure it's an integer

    // Query to fetch channel information from the database
    $result = $connect->query("SELECT * FROM tbl_channel WHERE id = $channel_id");
    $channel = $result->fetch_assoc(); // Fetch results as an associative array

    // Check if the channel exists
    if (!$channel) {
        die("Channel not found."); // Terminate the script if the channel doesn't exist
    }
} else {
    die("Invalid channel ID."); // Handle the case when the ID is not present
}

// Update channel information upon form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $channel_name = $_POST['channel_name']; // Get the channel name
    $channel_description = $_POST['channel_description']; // Get the channel description
    $channel_url = $_POST['channel_url']; // Get the channel URL
    $category_id = intval($_POST['category_id']); // Get the category ID and ensure it's an integer
    $channel_image = $_FILES['channel_image']['name']; // Get the image name

    // If there is a new image, upload it
    if ($channel_image) {
        // Move the uploaded file to the specified directory
        move_uploaded_file($_FILES['channel_image']['tmp_name'], "upload/" . basename($channel_image)); // Use basename for security
        // Update channel information with the new image
        $connect->query("UPDATE tbl_channel SET channel_name='$channel_name', category_id='$category_id', channel_image='$channel_image', channel_description='$channel_description', channel_url='$channel_url' WHERE id=$channel_id");
    } else {
        // Update channel information without changing the image
        $connect->query("UPDATE tbl_channel SET channel_name='$channel_name', category_id='$category_id', channel_description='$channel_description', channel_url='$channel_url' WHERE id=$channel_id");
    }

    // Redirect to the Channels page after update
    header("Location: Channels.php");
    exit; // Terminate the script
}

// Query to fetch all categories
$categories = $connect->query("SELECT * FROM tbl_category");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Channel</title>
    <link rel="stylesheet" href="assets/css/styles.min.css" />
    <link rel="stylesheet" href="assets/css/admin.css" />
</head>
<body>
    <div class="container">
        <h1>Edit Channel</h1>
        <form method="POST" enctype="multipart/form-data"> <!-- Form to handle file upload -->
            <div class="form-group">
                <label for="channel_name">Channel Name</label>
                <input type="text" class="form-control" id="channel_name" name="channel_name" value="<?php echo htmlspecialchars($channel['channel_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="channel_description">Channel Description</label>
                <textarea class="form-control" id="channel_description" name="channel_description" required><?php echo htmlspecialchars($channel['channel_description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="channel_url">Channel URL</label>
                <input type="text" class="form-control" id="channel_url" name="channel_url" value="<?php echo htmlspecialchars($channel['channel_url']); ?>" required>
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <?php while ($row = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $row['cid']; ?>" <?php if ($row['cid'] == $channel['category_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($row['category_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group form-row">
                <div>
                    <label for="channel_image">Channel Image</label>
                    <input type="file" class="form-control" id="channel_image" name="channel_image" onchange="previewImage(event)">
                </div>
                <div>
                    <label>Current Image:</label><br>
                    <img id="current_image" src="upload/<?php echo htmlspecialchars($channel['channel_image']); ?>" alt="<?php echo htmlspecialchars($channel['channel_name']); ?>" style="max-width: 100px;">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Channel</button>
        </form>
    </div>

    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(event) {
            const output = document.getElementById('current_image');
            output.src = URL.createObjectURL(event.target.files[0]); // Preview the uploaded image
        }
    </script>
</body>
</html>

<?php $connect->close(); // Close the database connection ?>

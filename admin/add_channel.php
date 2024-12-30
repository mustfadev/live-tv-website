<?php
ob_start();
include_once('includes/config.php'); // Include configuration file
include_once('includes/session_check.php'); // Check session

// Function to generate Slug from channel name
function generateSlug($channel_name) {
    // Convert to lowercase
    $slug = strtolower($channel_name);

    // Replace spaces with dashes
    $slug = str_replace(' ', '-', $slug);

    // Remove invalid characters
    $slug = preg_replace('/[^a-z0-9-]/', '', $slug);

    return $slug;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $channel_name = $_POST['channel_name'];
    $category_id = $_POST['category_id'];
    $channel_description = $_POST['channel_description'];
    $tags = $_POST['tags']; // Get tags input

    // Create slug from channel name
    $channel_slug = generateSlug($channel_name);

    // Image upload handling
    $target_dir = "upload/";
    $channel_image = basename($_FILES["channel_image"]["name"]);
    $target_file = $target_dir . $channel_image;

    // Check if the file is an actual image
    $check = getimagesize($_FILES["channel_image"]["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    }

    // Move the image to the specified directory
    if (move_uploaded_file($_FILES["channel_image"]["tmp_name"], $target_file)) {
        // Prepare the SQL query to insert channel data
        $sql = "INSERT INTO tbl_channel (category_id, channel_name, channel_slug, channel_image, channel_description, tags) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $connect->prepare($sql);
        
        // Check if the preparation was successful
        if ($stmt === false) {
            die("Error preparing the SQL query: " . $connect->error);
        }

        // Bind the variables
        $stmt->bind_param("isssss", $category_id, $channel_name, $channel_slug, $channel_image, $channel_description, $tags);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect after success
            header("Location: add_channel.php?success=1");
            exit(); // Ensure script ends after redirect
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
    }
}
?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Channel</title>
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="assets/css/styles.min.css" />
</head>
<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
         data-sidebar-position="fixed" data-header-position="fixed">
        
        <aside class="left-sidebar">
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="index.php" class="text-nowrap logo-img">
                        <img src="assets/images/logos/dark-logo.svg" width="180" alt="" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="true">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Home</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="index.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-layout-dashboard"></i>
                                </span>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Content</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="Channels.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-device-tv-old"></i>
                                </span>
                                <span class="hide-menu">Channels</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="Categories.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-category"></i>
                                </span>
                                <span class="hide-menu">Categories</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="Settings.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-settings"></i>
                                </span>
                                <span class="hide-menu">Settings</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        
        <div class="body-wrapper">
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item d-block d-xl-none">
                            <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                                <i class="ti ti-bell-ringing"></i>
                                <div class="notification bg-primary rounded-circle"></div>
                            </a>
                        </li>
                    </ul>
                    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                            <li class="nav-item dropdown">
                                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                    <img src="assets/images/userr.png" alt="" width="35" height="35" class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                                    <div class="message-body">
                                        <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-user fs-6"></i>
                                            <p class="mb-0 fs-3">My Profile</p>
                                        </a>
                                        <a href="logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>

            <div class="container-fluid">
                <div class="container">
                    <h1>Add New Channel</h1>

                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success">Channel added successfully.</div>
                    <?php endif; ?>

                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="channel_name" class="form-label">Channel Name</label>
                            <input type="text" class="form-control" id="channel_name" name="channel_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <?php
                                // Query to fetch all categories
                                $sql = "SELECT * FROM tbl_category";
                                $categories = $connect->query($sql);
                                while ($row = $categories->fetch_assoc()) {
                                    echo "<option value='" . $row['cid'] . "'>" . $row['category_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="channel_image" class="form-label">Channel Image</label>
                            <input type="file" class="form-control" id="channel_image" name="channel_image" accept="image/*" required>
                        </div>

                        <div class="mb-3">
                            <label for="channel_description" class="form-label">Channel Description</label>
                            <textarea class="form-control" id="channel_description" name="channel_description" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags (comma separated)</label>
                            <input type="text" class="form-control" id="tags" name="tags">
                        </div>

                        <button type="submit" class="btn btn-primary">Add Channel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/scripts.min.js"></script>
</body>
</html>

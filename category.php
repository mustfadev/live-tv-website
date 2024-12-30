<?php
//by mustfa dev
// Include configuration and text files for database connection and other settings
include_once('admin/includes/config.php');
include_once('admin/includes/texts.php');

// Check database connection
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Get the category ID from the request, ensuring it's a valid integer
$category_id = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);
if ($category_id === false) {
    die("Invalid category ID.");
}

// Fetch the category name
$sql_category = "SELECT category_name FROM tbl_category WHERE cid = ?";
$stmt_category = $connect->prepare($sql_category);

if (!$stmt_category) {
    error_log("Prepare failed: " . $connect->error);
    die("Internal Server Error. Please try again later.");
}

$stmt_category->bind_param("i", $category_id);
$stmt_category->execute();
$result_category = $stmt_category->get_result();

if ($result_category->num_rows === 0) {
    die("No category found for the given ID.");
}

// Fetch the category data
$category = $result_category->fetch_assoc();

// Start the HTML document
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['category_name']); ?> - Channels</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="sidebar-hidden">
<div class="container">
    <?php include_once('assets/header.php'); ?>
    <main class="main-layout">
        <div class="screen-overlay"></div>
        
        <?php include('assets/sidebar.php'); ?>
        
        <div class="content-wrapper">
            <center><br><h1 class="category-title"><?php echo htmlspecialchars($category['category_name']); ?></h1><br></center>
            <div class="vjsnsw">
                <?php
                // Fetch channels in the selected category
                $sql_channels = "SELECT w.* FROM tbl_channel w WHERE w.category_id = ?";
                $stmt_channels = $connect->prepare($sql_channels);
                $stmt_channels->bind_param("i", $category_id);
                $stmt_channels->execute();
                $result_channels = $stmt_channels->get_result();
                $tcount = $result_channels->num_rows;

                if ($tcount === 0) {
                    echo '<p align="center" style="font-size: 110%;">No channels to display in this category.</p>';
                } else {
                    ?>
                    <section class="content">
                        <div class="container-fluid">
                            <div class="body">
                                <div class="row">
                                    <?php while ($channel = mysqli_fetch_assoc($result_channels)) { ?>
                                        <div class="col-6 col-sm-4 col-md-3 mb-3">
                                            <div class="card h-100">
                                                <div class="card-img-top">
                                                    <img src="<?php echo ($channel['channel_image'] != '') ? 'admin/upload/' . htmlspecialchars($channel['channel_image']) : 'https://img.youtube.com/vi/' . htmlspecialchars($channel['video_id']) . '/mqdefault.jpg'; ?>" alt="<?php echo htmlspecialchars($channel['channel_name']); ?>" />
                                                    <a href="play.php?id=<?php echo htmlspecialchars($channel['id']); ?>" class="play-button" title="PLAY"></a>
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                    <?php echo htmlspecialchars($channel['channel_name']); ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php
                }
                // Close the prepared statements
                $stmt_category->close();
                $stmt_channels->close();
                ?>
            </div>
        </div>
        <br>
        <script src="assets/script.js"></script>
    </main>
</div>
</body>
</html>
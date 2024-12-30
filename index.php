<?php 
// by mustfa dev 
// Include configuration file for database connection
include_once('admin/includes/config.php');
include_once('admin/includes/texts.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row["name"]; ?></title> <!-- Display the page title -->
    <link rel="shortcut icon" type="image/png" href="admin/assets/images/logos/favicon.png" /> <!-- shortcut icon -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css"> <!-- Link to icon styles -->
    <link rel="stylesheet" href="assets/style.css"> <!-- Link to custom styles -->
</head>
<body class="sidebar-hidden">
<div class="container">
    <?php include_once('assets/header.php'); ?> <!-- Include the header/navbar section -->
    <main class="main-layout">
        <div class="screen-overlay"></div> <!-- Overlay for screen dimming -->
        <?php include('assets/sidebar.php'); ?> <!-- Include the sidebar navigation -->
        <div class="content-wrapper">
        <div class="category-list">
                <?php
                // Query to fetch all categories from the database
                $sql_categories = "SELECT * FROM tbl_category";
                $result_categories = $connect->query($sql_categories);
                
                // Loop through each category and display it as a link
                while ($category = mysqli_fetch_assoc($result_categories)) {
                    echo '<a href="category.php?category_id=' . htmlspecialchars($category['cid']) . '" class="category-button">' . htmlspecialchars($category['category_name']) . '</a>';
                }
                ?>
            </div>
            <div class="vjsnsw"> <!-- Container for the video list -->
                <?php
                // Handle keyword search
                $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : ''; // Get the search keyword
                
                if (!empty($keyword)) {
                    // Query to search channels by name
                    $sql = "SELECT w.*, c.category_name FROM tbl_channel w JOIN tbl_category c ON w.category_id = c.cid WHERE w.channel_name LIKE ?";
                    $stmt = $connect->prepare($sql); // Prepare SQL statement
                    $like_keyword = "%$keyword%"; // Prepare keyword for LIKE query
                    $stmt->bind_param("s", $like_keyword); // Bind parameters
                    $stmt->execute(); // Execute the statement
                    $result = $stmt->get_result(); // Get results
                } else {
                    // Default query to fetch all channels
                    $sql = "SELECT w.*, c.category_name FROM tbl_channel w JOIN tbl_category c ON w.category_id = c.cid ORDER BY w.id DESC";
                    $result = $connect->query($sql); // Execute the query
                }

                $tcount = mysqli_num_rows($result); // Total count of results
                ?>
                
                <section class="content">
                    <div class="container-fluid">
                        <div class="body">
                            <?php if ($tcount == 0) { ?>
                                <p align="center" style="font-size: 110%;">No channels to display.</p> <!-- No channels found -->
                            <?php } else { 
                                // Group channels by categories
                                $channelsByCategory = [];
                                while ($data = mysqli_fetch_assoc($result)) {
                                    $channelsByCategory[$data['category_name']][] = $data; // Group by category
                                }
                                // Display categories and their respective channels
                                foreach ($channelsByCategory as $category => $channels) { ?>
                                    <div class="category-section mb-5">
                                        <br>
                                        <h4 class="category-title"><?php echo htmlspecialchars($category); ?></h4> <!-- Display category title -->
                                        <hr class="category-divider"> <!-- Category separator -->
                                        <div class="row"> <!-- Row for channels -->
                                            <?php foreach ($channels as $channel) { ?>
                                                <div class="col-6 col-sm-4 col-md-3 mb-3">
                                                    <div class="card h-100"> <!-- Channel card -->
                                                        <div class="card-img-top">
                                                            <img src="<?php echo !empty($channel['channel_image']) ? 'admin/upload/' . htmlspecialchars($channel['channel_image']) : 'https://img.youtube.com/vi/' . htmlspecialchars($channel['video_id']) . '/mqdefault.jpg'; ?>" alt="<?php echo htmlspecialchars($channel['channel_name']); ?>" /> <!-- Channel image -->
                                                            <a href="play.php?slug=<?php echo htmlspecialchars($channel['channel_slug']); ?>" class="play-button" title="Watch channel"></a> <!-- Play button -->
                                                        </div>
                                                        <div class="card-body">
                                                        <h5 class="card-title">
                                      <?php echo htmlspecialchars($channel['channel_name']); ?></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </section>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
        </div>
        <script src="assets/script.js"></script> <!-- Link to custom scripts -->
    </main>
</div>
</body>
</html>

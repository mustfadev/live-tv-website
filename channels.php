<?php 
// Include configuration and text files for database connection and other settings
include_once('admin/includes/config.php');
include_once('admin/includes/texts.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($row) ? htmlspecialchars($row["name"]) : 'Channel List'; ?></title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="sidebar-hidden">
<div class="container">
    <?php include_once('assets/header.php'); // Include the header section ?>
    <main class="main-layout">
        <div class="screen-overlay"></div>
        
        <?php include('assets/sidebar.php'); // Include the sidebar navigation ?>
        
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

            <div class="vjsnsw">
                <?php
                // Check if a search keyword is provided
                $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
                if (!empty($keyword)) {
                    // Prepare a statement for searching channels by name
                    $sql = "SELECT w.*, c.category_name FROM tbl_channel w JOIN tbl_category c ON w.category_id = c.cid WHERE w.channel_name LIKE ?";
                    $stmt = $connect->prepare($sql);
                    $like_keyword = "%" . $keyword . "%"; // Create a like pattern for the search
                    $stmt->bind_param("s", $like_keyword); // Bind the parameter
                    $stmt->execute(); // Execute the statement
                    $result = $stmt->get_result(); // Get the result set
                } else {
                    // Fetch all channels if no keyword is provided
                    $sql = "SELECT w.*, c.category_name FROM tbl_channel w JOIN tbl_category c ON w.category_id = c.cid ORDER BY w.id DESC";
                    $result = $connect->query($sql); // Execute the query
                }

                // Count the number of rows returned
                $tcount = mysqli_num_rows($result);
                ?>
                
                <br><br>
                <section class="content">
                    <div class="container-fluid">
                        <div class="body">
                            <?php if ($tcount === 0) { // Check if there are no channels ?>
                                <p align="center" style="font-size: 110%;">No channels to display.</p>
                            <?php } else { ?>
                                <div class="row">
                                    <?php while ($channel = mysqli_fetch_assoc($result)) { // Loop through each channel ?>
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
                            <?php } ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <br><br>
        <script src="assets/script.js"></script> <!-- Include external JavaScript -->
    </main>
</div>
</body>
</html>

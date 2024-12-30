<?php 
// Include the database
include_once('admin/includes/config.php');

// Query to retrieve the name from the database
$sql = "SELECT name FROM strings WHERE id = 1";
$result = $connect->query($sql);
$row = $result->fetch_assoc();
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
    <style>
        /* نافذة الجودة */
        .quality-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 8px;
            width: 300px;
            padding: 20px;
            z-index: 1000;
            text-align: center;
        }

        .quality-popup button {
            display: block;
            width: 90%;
            padding: 10px;
            margin: 10px auto;
            font-size: 25px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .quality-popup button:hover {
            background-color: #0056b3;
        }

        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .quality-select {
            position: absolute; /* لتحريك الزر بسهولة */
            bottom: -48px; /* المسافة من الأسفل */
            right: -2px; /* المسافة من اليمين */
            padding: 10px 15px;
            font-size: 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .quality-select:hover {
            background-color: #0056b3;
        }

        /* صندوق الوصف */
        .description-container {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: left;
            font-size: 14px;
            line-height: 1.6;
        }

        .description-container h3 {
            margin-bottom: 10px;
            font-size: 18px;
            color: #333;
        }

        .description-container p {
            margin-bottom: 10px;
            color: #666;
        }

        /* العنوان */
        h1 {
            color: #0056b3;
            display: inline-block;
            font-size: 20px;
            margin-top: 10px;
        }

        /* الفيديو */
        .video-container {
            position: relative;
            width: 100%;
            height: 400px;
            border: 1px solid #ccc;
        }

        /* مشغل الفيديو */
        #vid {
            width: 100%;
            height: 100%;
        }

        /* أزرار داخل النافذة المنبثقة */
        .quality-popup button {
            transition: background-color 0.3s ease;
        }

        /* التأثير عند تمرير الماوس على زر الجودة */
        .quality-popup button:hover {
            background-color: #004c99;
        }
    </style>
</head>

<body class="sidebar-hidden">
<div class="container">
    <?php include_once('assets/header.php'); ?>
    <main class="main-layout">
        <div class="screen-overlay"></div>
        <?php include('assets/sidebar.php'); ?>
        <div class="content-wrapper">

        <?php 
        function clean($data) {
            return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
        }

        // تعديل لقراءة الـ slug بدلاً من الـ ID
        if (isset($_GET['slug'])) {
            $slug = clean($_GET['slug']);
            $sql_channel = "SELECT * FROM tbl_channel WHERE channel_slug = '$slug'"; // استخدمنا الـ slug هنا
            $result = $connect->query($sql_channel);
            $row = $result->fetch_assoc();
        }

        // Get the first link (link_id = 1)
        $sql_links = "SELECT * FROM tbl_channel_links WHERE channel_id = '".$row['id']."' ORDER BY link_id ASC LIMIT 1";
        $first_link_result = $connect->query($sql_links);
        $first_link = $first_link_result->fetch_assoc();

        // Get all other links for quality selection
        $sql_all_links = "SELECT * FROM tbl_channel_links WHERE channel_id = '".$row['id']."'";
        $all_links_result = $connect->query($sql_all_links);
        ?>

        <div class="video-container">
            <div id="vid"></div>

            <!-- اسم البث -->
            <div>
                <h1><?php echo htmlspecialchars($row['channel_name']); ?></h1>
                <button class="quality-select" onclick="openPopup()">Quality</button>
            </div>

            <!-- Quality select button -->
            <!-- Quality selection popup -->
            <div class="popup-overlay" id="popupOverlay"></div>
            <div class="quality-popup" id="qualityPopup">
                <?php 
                    while ($link = $all_links_result->fetch_assoc()) {
                ?>
                    <button onclick="playVideo('<?php echo htmlspecialchars($link['link_url']); ?>')">
                        <?php echo htmlspecialchars($link['link_name']); ?>
                    </button>
                <?php } ?>
                <button onclick="closePopup()">Close</button>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/clappr/latest/clappr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/clappr.level-selector/latest/level-selector.min.js"></script>
          
        <script>
            let player;

            function openPopup() {
                document.getElementById('popupOverlay').style.display = 'block';
                document.getElementById('qualityPopup').style.display = 'block';
            }

            function closePopup() {
                document.getElementById('popupOverlay').style.display = 'none';
                document.getElementById('qualityPopup').style.display = 'none';
            }

            function playVideo(url) {
                if (player) {
                    player.destroy();
                }

                player = new Clappr.Player({
                    source: url,
                    autoPlay: true,
                    height: "400",
                    width: "100%",
                    parentId: "#vid"
                });

                closePopup();
            }

            // Play the first video link automatically when the page loads
            window.onload = function() {
                if (<?php echo json_encode($first_link); ?>) {
                    playVideo('<?php echo htmlspecialchars($first_link['link_url']); ?>');
                }
            };
        </script>

        <!-- Description Section -->
        <br>
        <br>
        <br>
        <div class="description-container">
            <h3>Description:</h3>
            <p><?php echo htmlspecialchars($row['channel_description']); ?></p>
        </div>
        <br>
        <br>

        </center>
        
        </div>
      </div>
      <script src="assets/script.js"></script> <!-- Link to custom scripts -->
</body>
</html>

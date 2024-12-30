<?php include_once('admin/includes/config.php');

$sql = "SELECT name FROM strings WHERE id = 1";
$result = $connect->query($sql);


$row = $result->fetch_assoc();


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $row["name"]; ?></title>
  <!-- Linking Unicons For Icons -->
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body class="sidebar-hidden">
  <div class="container">
    <!-- Header / Navbar -->
    <?php include_once('assets/header.php'); ?>
    
    <!-- Main Layout -->
    <main class="main-layout">
      <div class="screen-overlay"></div>

      <!-- Sidebar -->
      <?php include('assets/sidebar.php'); ?>
  
      <div class="content-wrapper">
        <!-- Category List -->
        <div class="category-list">
        </div>
        
        <!-- Video List -->
        <div class="vjsnsw">

        <?php

if (isset($_GET['keyword'])) {
    $keyword = htmlspecialchars($_GET['keyword']); 

   
    $sql = "SELECT w.*, c.category_name FROM tbl_channel w JOIN tbl_category c ON w.category_id = c.cid WHERE w.channel_name LIKE '%$keyword%' LIMIT 10"; // تحديد عدد النتائج
    $result = $connect->query($sql);
    
    
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<h2>Search results for: $keyword</h2>";
        echo "<br>";
        echo "<div class='row'>";
        
        while ($channel = mysqli_fetch_assoc($result)) {
            echo "<div class='col-6 col-sm-4 col-md-3 mb-3'>";
            echo "<div class='card h-100'>";
            echo "<div class='card-img-top'>";
            echo "<img src='" . ($channel['channel_image'] != '' ? 'admin/upload/' . $channel['channel_image'] : 'https://img.youtube.com/vi/' . $channel['video_id'] . '/mqdefault.jpg') . "' alt='" . $channel['channel_name'] . "' />";
            echo "<a href='play.php?id=" . $channel['id'] . "' class='play-button' title='مشاهدة القناة'></a>";
            echo "</div>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>" . ($channel['channel_status'] == '1' ? $channel['channel_name'] : '<span class="text-muted"><del>' . $channel['channel_name'] . '</del></span>') . "</h5>";
            echo "<p class='category'>" . htmlspecialchars($channel['category_name']) . "</p>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        
        echo "</div>"; 
    } else {
        echo "<p>There are no channels matching your search.</p>";
    }
} else {
    echo "<p>Please enter a search term.</p>";
}
?>


            
      

        </div>
        <br>
        <br>

  <!-- Linking custom script -->
  <script src="assets/script.js"></script>
</body>
</html>
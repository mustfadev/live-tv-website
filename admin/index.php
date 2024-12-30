<?php
include_once('includes/config.php'); // Include database connection settings
include_once('includes/session_check.php'); // Include session validation
include_once('includes/texts.php'); // Include any required text settings

// Fetch the site name (assuming $row is set in texts.php)
if (isset($row["name"])) {
    $siteName = htmlspecialchars($row["name"]); // Sanitize output for safety
} else {
    $siteName = "Dashboard"; // Default name
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $siteName; ?></title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
</head>

<body>
  <!-- Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    
    <?php include('includes/sidebar.php'); ?> <!-- Sidebar -->

    <!-- Main wrapper -->
    <div class="body-wrapper">
      <!-- Header Start -->
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
      <!-- Header End -->

      <div class="container-fluid text-center"> <!-- Center content -->
        <br><br>
        <div class="row">

          <!-- Card for Channels Count -->
          <div class="col-md-6">
            <div class="card text-center border-primary shadow-sm" style="border: 2px solid silver;">
              <div class="card-body">
                <h5 class="card-title text-primary">Number of Channels</h5>
                <i class="ti ti-device-tv-old" style="font-size: 50px; color: #007bff;"></i>
                <?php
                  // Get the count of channels
                  $query = "SELECT COUNT(*) as count FROM tbl_channel"; 
                  $result = mysqli_query($connect, $query);
                  $data = mysqli_fetch_assoc($result);
                  $channelsCount = $data['count'] ?? 0; // Use null coalescing for safety
                ?>
                <p class="card-text"><?php echo $channelsCount; ?> Channels</p>
              </div>
            </div>
          </div>

          <!-- Card for Categories Count -->
          <div class="col-md-6">
            <div class="card text-center border-primary shadow-sm" style="border: 2px solid silver;">
              <div class="card-body">
                <h5 class="card-title text-primary">Number of Categories</h5>
                <i class="ti ti-category" style="font-size: 50px; color: #007bff;"></i>
                <?php
                  $query = "SELECT COUNT(*) as count FROM tbl_category"; 
                  $result = mysqli_query($connect, $query);
                  $data = mysqli_fetch_assoc($result);
                  $categoriesCount = $data['count'] ?? 0; // Use null coalescing for safety
                ?>
                <p class="card-text"><?php echo $categoriesCount; ?> Categories</p>
              </div>
            </div>
          </div>

          <!-- Card for Admins Count -->
          <div class="col-md-6">
            <div class="card text-center border-primary shadow-sm" style="border: 2px solid silver;">
              <div class="card-body">
                <h5 class="card-title text-primary">Number of Admins</h5>
                <i class="ti ti-user" style="font-size: 50px; color: #007bff;"></i>
                <?php
                  $query = "SELECT COUNT(*) as count FROM users"; 
                  $result = mysqli_query($connect, $query);
                  $data = mysqli_fetch_assoc($result);
                  $adminsCount = $data['count'] ?? 0; // Use null coalescing for safety
                ?>
                <p class="card-text"><?php echo $adminsCount; ?> Admins</p>
              </div>
            </div>
          </div>

          <!-- Card for Site Visits -->
          <div class="col-md-6">
            <div class="card text-center border-primary shadow-sm" style="border: 2px solid silver;">
              <div class="card-body">
                <h5 class="card-title text-primary">Site Visits</h5>
                <i class="ti ti-telescope" style="font-size: 50px; color: #007bff;"></i>
                <p class="card-text"><?php echo $_SESSION['total_visits'] ?? 0; ?> Total Visits</p>
              </div>
            </div>
          </div>

        </div>
      </div>

      <br><br>

      <div class="py-6 px-6 text-center">
        <!-- Optional footer or additional content -->
      </div>
    </div>
  </div>

  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/sidebarmenu.js"></script>
  <script src="assets/js/app.min.js"></script>
  <script src="assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="assets/js/dashboard.js"></script>
</body>

</html>

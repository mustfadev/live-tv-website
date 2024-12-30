<?php 
include_once('includes/config.php'); // Include database configuration file
include_once('includes/session_check.php'); // Include session check to verify user login status
include_once('includes/texts.php'); // Include any text or translation definitions
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $row["name"]; ?></title> <!-- Set the page title dynamically -->
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" /> <!-- Favicon for the website -->
  <link rel="stylesheet" href="assets/css/styles.min.css" /> <!-- Link to main CSS stylesheet -->
</head>

<body>
  <!-- Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    
    <!-- Sidebar Start -->
    <?php include ('includes/sidebar.php'); // Include sidebar for navigation ?>
    <!-- Sidebar End -->
    
    <!-- Main wrapper -->
    <div class="body-wrapper">
      <!-- Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i> <!-- Sidebar toggle button -->
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                <i class="ti ti-bell-ringing"></i> <!-- Notification icon -->
                <div class="notification bg-primary rounded-circle"></div> <!-- Notification indicator -->
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="assets/images/userr.png" alt="" width="35" height="35" class="rounded-circle"> <!-- User profile image -->
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i> <!-- Profile icon -->
                      <p class="mb-0 fs-3">My Profile</p> <!-- Profile link -->
                    </a>
                    <a href="logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a> <!-- Logout button -->
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Header End -->
      
      <div class="container-fluid">
        <a href="add_channel.php" class="btn btn-primary">Add New Channel</a> <!-- Button to add new channel -->
        <br><br>
        <h1>Channel List</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Channel ID</th> <!-- Column for Channel ID -->
                    <th>Channel Name</th> <!-- Column for Channel Name -->
                    <th>Channel Image</th> <!-- Column for Channel Image -->
                    <th>Category</th> <!-- Column for Channel Category -->
                    <th>Actions</th> <!-- Column for Action Buttons -->
                    
                </tr>
            </thead>
            <tbody>
                <?php
                // Delete channel process
                if (isset($_GET['delete_id'])) {
                    $delete_id = $_GET['delete_id']; // Get the ID of the channel to delete
                    $connect->query("DELETE FROM tbl_channel WHERE id = $delete_id"); // Execute deletion
                }

                // Query to fetch all channels along with their categories
                $sql = "SELECT c.*, cat.category_name FROM tbl_channel c
                        LEFT JOIN tbl_category cat ON c.category_id = cat.cid"; // SQL to join channels with categories
                $result = $connect->query($sql); // Execute the query

                if ($result->num_rows > 0): // Check if any channels are available
                    while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td> <!-- Display Channel ID -->
                        <td><?php echo $row['channel_name']; ?></td> <!-- Display Channel Name -->
                        <td><img src="upload/<?php echo $row['channel_image']; ?>" alt="<?php echo $row['channel_name']; ?>" width="100"></td> <!-- Display Channel Image -->
                        <td><?php echo $row['category_name']; ?></td> <!-- Display Category Name -->
                        <td>
                            <a href="edit_channel.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a> <!-- Edit button -->
                            <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this channel?')">Delete</a> <!-- Delete button with confirmation -->
                            <a href="edit_links.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit Links</a>

                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No channels available.</td> <!-- Message if no channels are found -->
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
      </div>
    </div>
  </div>

  <script src="assets/libs/jquery/dist/jquery.min.js"></script> <!-- jQuery library -->
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JavaScript -->
  <script src="assets/js/sidebarmenu.js"></script> <!-- Sidebar menu JavaScript -->
  <script src="assets/js/app.min.js"></script> <!-- Main application JavaScript -->
  <script src="assets/libs/apexcharts/dist/apexcharts.min.js"></script> <!-- Charting library -->
  <script src="assets/libs/simplebar/dist/simplebar.js"></script> <!-- Custom scrollbar -->
  <script src="assets/js/dashboard.js"></script> <!-- Dashboard specific JavaScript -->
</body>

</html>

<?php $connect->close(); ?> <!-- Close the database connection -->

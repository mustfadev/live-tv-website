<?php 
include_once('includes/config.php'); // Include database configuration file
include_once('includes/session_check.php'); // Include session verification to ensure user is logged in
include_once('includes/texts.php'); // Include any text definitions or translations
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $row["name"]; ?></title> <!-- Set the page title dynamically from the database -->
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" /> <!-- Favicon for the website -->
  <link rel="stylesheet" href="assets/css/styles.min.css" /> <!-- Link to the main CSS stylesheet -->
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    
    <!-- Sidebar Start -->
    <?php include ('includes/sidebar.php'); // Include sidebar for navigation ?>
    <!--  Sidebar End -->

    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
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
                      <i class="ti ti-user fs-6"></i> <!-- User profile icon -->
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
        <a href="add_category.php" class="btn btn-primary">Add New Category</a> <!-- Button to navigate to add new category page -->
        <br><br>
        <h1>Category List</h1>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Category ID</th> <!-- Column for Category ID -->
              <th>Category Name</th> <!-- Column for Category Name -->
              <th>Actions</th> <!-- Column for action buttons -->
            </tr>
          </thead>
          <tbody>
            <?php
            // Delete category process
            if (isset($_GET['delete_id'])) {
                $delete_id = $_GET['delete_id']; // Get the ID of the category to delete

                // Check if there are channels associated with this category
                $checkChannels = $connect->query("SELECT COUNT(*) AS channel_count FROM tbl_channel WHERE category_id = $delete_id");
                $row = $checkChannels->fetch_assoc();

                // If there are channels, display an alert
                if ($row['channel_count'] > 0) {
                    echo "<script>alert('Cannot delete category because there are channels associated with it.');</script>";
                } else {
                    // If no channels, proceed to delete the category
                    $connect->query("DELETE FROM tbl_category WHERE cid = $delete_id");
                    echo "<script>alert('Category deleted successfully.');</script>";
                }
            }

            // Query to fetch all categories
            $sql = "SELECT * FROM tbl_category"; // SQL statement to get all categories
            $result = $connect->query($sql);

            if ($result->num_rows > 0): // Check if there are categories
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['cid']; ?></td> <!-- Display Category ID -->
                        <td><?php echo $row['category_name']; ?></td> <!-- Display Category Name -->
                        <td>
                            <a href="edit_category.php?id=<?php echo $row['cid']; ?>" class="btn btn-primary">Edit</a> <!-- Edit button -->
                            <a href="?delete_id=<?php echo $row['cid']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a> <!-- Delete button with confirmation -->
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No categories available.</td> <!-- Message if no categories are found -->
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
  <script src="assets/libs/simplebar/dist/simplebar.js"></script> <!-- Simplebar for custom scrollbar -->
  <script src="assets/js/dashboard.js"></script> <!-- Dashboard specific JavaScript -->
</body>

</html>

<?php $connect->close(); ?> <!-- Close the database connection -->

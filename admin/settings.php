<?php
// Include the necessary configuration and session check files
include_once('includes/config.php');
include_once('includes/session_check.php');
include_once ('includes/texts.php');

// Check if the database connection is successful
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error()); // Stop execution and display error if connection fails
}

$success_message = ""; // Initialize a variable to store success messages

// Handle form submission when the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape special characters in the input to prevent SQL injection
    $new_name = $connect->real_escape_string($_POST['name']); 
    // Prepare the SQL query to update the name in the database
    $sql = "UPDATE strings SET name='$new_name' WHERE id=1";
    // Execute the query and check for success
    if ($connect->query($sql) === TRUE) {
        $success_message = "Name updated successfully!"; // Success message
    } else {
        $success_message = "Error updating name: " . $connect->error; // Error message on failure
    }
}

// Prepare a query to retrieve the current name from the database
$sql = "SELECT name FROM strings WHERE id = 1";
$result = $connect->query($sql);

// Check if the result is valid and has rows
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc(); // Fetch the row as an associative array
    $current_name = $row["name"]; // Store the current name
} else {
    $current_name = "Name not found"; // Default message if no record found
}

// Close the database connection
$connect->close();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $row["name"]; ?></title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
</head>
<script>
// JavaScript function to submit the form when the button is clicked
function saveName() {
    document.getElementById("nameForm").submit();
}
</script>
<style>
      
        h1 {
            color: #007bff;
        }
       
        #nameInput {
            border: 1px solid #007bff;
            padding: 10px;
            border-radius: 5px;
            width: 200px; 
            margin-right: 10px; 
        }
        #saveButton {
            padding: 10px 15px;
            background-color: #007bff; 
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #saveButton:hover {
            background-color: #0056b3; 
        }
        .success-message {
            margin-top: 20px;
            color: #28a745; 
        }
        label {
            font-weight: bold;
            margin-right: 10px;
        }
    </style>

<body>
  <!-- Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">

    <!-- Sidebar Start -->
    <?php include ('includes/sidebar.php'); ?>
    <!-- Sidebar End -->
      
    <!-- Main wrapper -->
    <div class="body-wrapper">
      <!-- Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i> <!-- Sidebar toggle icon -->
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                <i class="ti ti-bell-ringing"></i>
                <div class="notification bg-primary rounded-circle"></div> <!-- Notification indicator -->
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
      <div class="container-fluid">
        
          <br>
          <br>
          
          <div class="card mb-0">
                <div class="card-body">
                  <form id="nameForm" method="post"> <!-- Form to update the site name -->
                      <div class="mb-3">
                        <label for="disabledTextInput" class="form-label">Site Name:</label>
                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($current_name); ?>" required>
                      </div>
                      <?php if ($success_message): ?>
                          <div class="success-message"><?php echo $success_message; ?></div> <!-- Display success message if available -->
                      <?php endif; ?>

                      <button type="button" id="saveButton" onclick="saveName()" class="btn btn-primary">Save</button>
                  </form>
                </div>
              </div>
             </div>
         
        </div>
      </div>
    </div>
  </div>
  <!-- Include JavaScript libraries -->
  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/sidebarmenu.js"></script>
  <script src="assets/js/app.min.js"></script>
  <script src="assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="assets/js/dashboard.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
</body>
</html>

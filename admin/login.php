<?php
// Include database connection and texts
include_once('includes/config.php');
include_once('includes/texts.php');
session_start();

// Initialize an empty error message
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize user input
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Prepare the statement to check username and password
    $stmt = $connect->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verify user credentials
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Incorrect password.";
        }
    } else {
        $error_message = "Username does not exist.";
    }

    $stmt->close();
}

$connect->close();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($row["name"]); ?></title> <!-- Sanitize site name -->
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <style>
      .btn-custom {
          background-color: #007bff; 
          color: white;
          font-size: 1.5rem; 
          padding: 15px 30px; 
          width: 100%; 
          border: none; 
          border-radius: 5px; 
      }
      .btn-custom:hover {
          background-color: #0056b3; 
      }
      .error-message {
          color: red;
          text-align: center;
          margin-top: 10px;
      }
  </style>
</head>

<body>
  <!-- Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="assets/images/logos/favicon.png" alt="" style="width: 35px; height: 35px;">
                  <h1>CCLIVE TV</h1>
                </a>

                <?php if ($error_message): ?>
                  <div class="error-message"><?= htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username" required>
                  </div>
                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                  </div>
                  <button type="submit" class="btn btn-custom">Login</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

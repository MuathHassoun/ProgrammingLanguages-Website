<?php
  use JetBrains\PhpStorm\NoReturn;

  session_start();
  if (isset($_SESSION['login_msg'])) {
    $msg = $_SESSION['login_msg'];
    unset($_SESSION['login_msg']);

    echo '<script src="../../js/actions/sweet-alert2-js.js"></script>';
    echo "<script>
          window.onload = function() {
            showAlert('info', '".htmlspecialchars($msg)."', '', '#0f1b30');
          };
        </script>";
  }
  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: ../../index.php");
    exit();
  }

  #[NoReturn] function afterSuccess(): void {
    if (isset($_SESSION['send_from'])) {
      if ($_SESSION['send_from'] == "edit-languages-page") {
        unset($_SESSION['send_from']);
        header("Location: EditLanguages.php");
      } elseif ($_SESSION['send_from'] == "contact-us-page") {
        unset($_SESSION['send_from']);
        header("Location: ContactUs.php");
      }
    } else {
      header("Location: ../../index.php");
    }

    echo <<<HTML
            <script src="../../js/actions/authentication-js.js"></script>
            <script>
              document.addEventListener("DOMContentLoaded", function () {
                switchAuth();
                logout();
              });
            </script>
          HTML;
    exit();
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = require_once '../database-dir/connect.php';
    if ($_SESSION['db_connected'] === false) {
      error_log("Database connection failed: " . $conn->connect_error);
      $_SESSION['error_message'] = "We encountered a technical issue while processing your request. Please try again later.";
      header("Location: /php-pages/client-side/display_error.php");
      exit;
    }

    if (isset($_POST['login'])) {
      $username = $_POST['username'] ?? '';
      $password = $_POST['password'] ?? '';
      if (str_contains($username, "admin")) {
        $stmt = $conn->prepare("SELECT username, password FROM admins WHERE username = ? LIMIT 1");
      } else {
        $stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ? LIMIT 1");
      }

      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();
      $user = $result->fetch_assoc();
      $stmt->close();

      if ($user && password_verify($password, $user['password'])) {
        $_SESSION['successful_msg'] = "Login successful!|Welcome, " . htmlspecialchars($user['username']);
        $_SESSION['logged_in'] = true;
        $_SESSION['new-user'] = false;
        $_SESSION['username'] = $user['username'];
        if (str_contains($username, "admin")) {
          $_SESSION['isAdmin'] = true;
        } else {
          $_SESSION['isAdmin'] = false;
        }

        $conn->close();
        afterSuccess();
      } else {
        echo '<script src="../../js/actions/sweet-alert2-js.js"></script>';
        echo "<script>
              window.onload = function() {
                showAlert('error', 'Invalid username or password.', 'Please check your credentials and try again.', '#0f1b30');
              };
            </script>";
      }
    } elseif (isset($_POST['register'])) {
      $username = $_POST['username'] ?? '';
      $email    = $_POST['email'] ?? '';
      $password = $_POST['password'] ?? '';

      if ($username && $email && $password) {
        if (strlen($username) < 10 || strlen($username) > 15) {
          echo '<script src="../../js/actions/sweet-alert2-js.js"></script>';
          echo "<script>
                window.onload = function() {
                  showAlert('warning', 'Invalid Username Length', 'Username must be between 10 and 15 characters.', '#f0ad4e');
                };
              </script>";
        } else {
          if (str_contains($username, "admin")) {
            $account_stmt = $conn->prepare("INSERT INTO accounts (username, role) VALUES (?, 'admin')");
            $stmt = $conn->prepare("INSERT INTO admins (username, email, password) VALUES (?, ?, ?)");
          } else {
            $account_stmt = $conn->prepare("INSERT INTO accounts (username, role) VALUES (?, 'user')");
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
          }
          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
          $stmt->bind_param("sss", $username, $email, $hashedPassword);
          $account_stmt->bind_param("s", $username);

          try {
            $account_stmt->execute();
            $account_stmt->close();

            if ($stmt->execute()) {
              $_SESSION['successful_msg'] = "Registration successful!|Welcome, " . htmlspecialchars($username);
              $_SESSION['logged_in'] = true;
              $_SESSION['new-user'] = true;
              $_SESSION['username'] = $username;
              if (str_contains($username, "admin")) {
                $_SESSION['isAdmin'] = true;
              } else {
                $_SESSION['isAdmin'] = false;
              }

              $stmt->close();
              $conn->close();
              afterSuccess();
            } else {
              echo '<script src="../../js/actions/sweet-alert2-js.js"></script>';
              echo "<script>
                  window.onload = function() {
                    showAlert('error', 'Registration failed!', 'Error: " . htmlspecialchars($stmt->error) . "', '#b02a37');
                  };
                </script>";
            }
          } catch (Exception $e) {
            error_log("Registration failed: " . $e->getMessage());
            $_SESSION['error_message'] = "An error occurred while completing your registration. Please try again later.\n" . $e->getMessage();
            header("Location: /php-pages/client-side/display_error.php");
            exit;
          }
        }
      } else {
        echo '<script src="../../js/actions/sweet-alert2-js.js"></script>';
        echo "<script>
              window.onload = function() {
                showAlert('warning', 'Incomplete data!', 'Please fill in all required fields.', '#f0ad4e');
              };
            </script>";
      }
    }
    $conn->close();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login/Signup Form</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/embellishment-style.css" />
  <link rel="stylesheet" href="../../css/waves-style.css" />
  <link rel="stylesheet" href="../../css/authentication-style.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="icon" href="../../img/icon/icon.png" type="image/x-icon" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <!-- Header -->
  <header>
    <div class="logo">
      <a href="../../index.php" class="logo-link">
        <img class="logo-icon" src="../../img/icon/icon.png" alt="CodeWorld Logo" />
        <h1 class="website-title">CodeWorld!</h1>
      </a>
      <a href="../../index.php#learn-section" class="section-subtitle-link">
        <h1 class="section-subtitle">Learn Programming Languages</h1>
      </a>

      <section class="x-container">
        <section class="x-dimension">
          <div class="xcircle" style="--i:0;"></div>
          <div class="xcircle" style="--i:1;"></div>
          <div class="xcircle" style="--i:2;"></div>
          <div class="xcircle" style="--i:3;"></div>
          <div class="xcircle" style="--i:4;"></div>
          <div class="xcircle" style="--i:5;"></div>
          <div class="xcircle" style="--i:6;"></div>
          <div class="xcircle" style="--i:7;"></div>
          <div class="xcircle" style="--i:8;"></div>
          <div class="xcircle" style="--i:9;"></div>
          <div class="xcircle" style="--i:10;"></div>
        </section>
      </section>
    </div>

    <nav>
      <div class="nav-center">
        <a href="../../index.php">Home</a>
        <a href="Languages.php#languages-section">Learn Languages</a>
        <a href="Documentation.php#doc-section">Documentation</a>
        <a href="EditLanguages.php#edit-section">Edit/New</a>
        <a href="Compare.php#compare-section">Compare</a>
        <a href="AboutUs.php#about-us-section">About Us</a>
        <a href="ContactUs.php#contact-us-section">Contact Us</a>
      </div>
      <div class="nav-auth">
        <a href="Logout.php" class="logout-not-active">Logout</a>
      </div>
    </nav>
  </header>

  <main class="main-container" id="authentication-section">
    <div class="container">
      <div class="form-box login fade-in">
        <form action="#" method="post">
          <h1>Login</h1>
          <div class="input-box">
            <input type="text" name="username" placeholder="Username (10-15 characters)"
                   minlength="10" maxlength="15" required oninput="validateUsername(this)">
            <i class='bx bxs-user'></i>
            <small id="usernameWarning" class="warning">
              Username must be between 10 and 15 characters.
            </small>
          </div>
          <div class="input-box">
            <input type="password" name="password" placeholder="Password" required>
            <i class='bx bxs-lock-alt'></i>
          </div>
          <div class="forgot-link">
            <a href="ForgotPassword.php#forgot-password-section">Forgot Password?</a>
          </div>
          <button type="submit" name="login" class="btn">Login</button>
          <p>or login with social platforms</p>
          <div class="social-icons">
            <a href="https://accounts.google.com/o/oauth2/v2/auth?client_id=YOUR_GOOGLE_CLIENT_ID&redirect_uri=YOUR_REDIRECT_URI&response_type=code&scope=email%20profile">
              <i class='bx bxl-google'></i>
            </a>
            <a href="https://www.facebook.com/v13.0/dialog/oauth?client_id=YOUR_FACEBOOK_APP_ID&redirect_uri=YOUR_REDIRECT_URI&state=YOUR_STATE_PARAM&response_type=code&scope=email">
              <i class='bx bxl-facebook'></i>
            </a>
            <a href="https://github.com/login/oauth/authorize?client_id=YOUR_GITHUB_CLIENT_ID&redirect_uri=YOUR_REDIRECT_URI&scope=user">
              <i class='bx bxl-github'></i>
            </a>
            <a href="https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=YOUR_LINKEDIN_CLIENT_ID&redirect_uri=YOUR_REDIRECT_URI&scope=r_liteprofile%20r_emailaddress">
              <i class='bx bxl-linkedin'></i>
            </a>
          </div>
        </form>
      </div>
      <div class="form-box register fade-in">
        <form action="#" method="post">
          <h1>Registration</h1>
          <div class="input-box">
            <input type="text" name="username" placeholder="Username (10-15 characters)"
                   minlength="10" maxlength="15" required oninput="validateUsername(this)">
            <i class='bx bxs-user'></i>
            <small id="usernameWarning" class="warning">
              Username must be between 10 and 15 characters.
            </small>
          </div>
          <div class="input-box">
            <input type="email" name="email" placeholder="Email" required>
            <i class='bx bxs-envelope'></i>
          </div>
          <div class="input-box">
            <input type="password" name="password" placeholder="Password" required>
            <i class='bx bxs-lock-alt'></i>
          </div>
          <button type="submit" name="register" class="btn">Register</button>
          <p>or register with social platforms</p>
          <div class="social-icons">
            <a href="https://accounts.google.com/o/oauth2/v2/auth?client_id=YOUR_GOOGLE_CLIENT_ID&redirect_uri=YOUR_REDIRECT_URI&response_type=code&scope=email%20profile">
              <i class='bx bxl-google'></i>
            </a>
            <a href="https://www.facebook.com/v13.0/dialog/oauth?client_id=YOUR_FACEBOOK_APP_ID&redirect_uri=YOUR_REDIRECT_URI&state=YOUR_STATE_PARAM&response_type=code&scope=email">
              <i class='bx bxl-facebook'></i>
            </a>
            <a href="https://github.com/login/oauth/authorize?client_id=YOUR_GITHUB_CLIENT_ID&redirect_uri=YOUR_REDIRECT_URI&scope=user">
              <i class='bx bxl-github'></i>
            </a>
            <a href="https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=YOUR_LINKEDIN_CLIENT_ID&redirect_uri=YOUR_REDIRECT_URI&scope=r_liteprofile%20r_emailaddress">
              <i class='bx bxl-linkedin'></i>
            </a>
          </div>
        </form>
      </div>

      <div class="toggle-box">
        <div class="toggle-panel toggle-left">
          <h1>Hello, Welcome!</h1>
          <p>Don't have an account?</p>
          <button class="btn register-btn">Register</button>
        </div>
        <div class="toggle-panel toggle-right">
          <h1>Welcome Back!</h1>
          <p>Already have an account?</p>
          <button class="btn login-btn">Login</button>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer>
    <p>© 2025 Learn Programming Languages - All Rights Reserved</p>
  </footer>
  <!-- Water Wave -->
  <div class="waves"></div>
  <script src="../../js/actions/authentication-js.js"></script>
</body>
</html>

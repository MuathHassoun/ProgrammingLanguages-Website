<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$conn = require(__DIR__ . '/../database-dir/connect.php');
if (!$conn || $_SESSION['db_connected'] === false) {
  error_log("Database connection failed or returned false");
  $_SESSION['error_message'] = "We encountered a technical issue while processing your request. Please try again later.";
  header("Location: /php-pages/client-side/display_error.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $fullName = trim($_POST['admin-full_name']);
  $email = trim($_POST['admin-email']);
  $username = trim($_POST['admin-username']);
  $hashedPassword = password_hash(trim($_POST['admin-password']), PASSWORD_BCRYPT);

  include_once(__DIR__ . '/../admin-side/admin-default-data.php');
  $photo      = file_get_contents($_SESSION['admin-photo']);
  $shortIntro = $_SESSION['short_self_introduction'];
  $longIntro  = $_SESSION['long_self_introduction'];

  if (empty($fullName) || empty($email) || empty($username) || empty($hashedPassword)) {
    $_SESSION['error_message'] = "All fields are required.";
    header("Location: /php-pages/client-side/display_error.php");
    exit;
  }

  $insertStmt = $conn->prepare("INSERT INTO accounts (username, role) VALUES (?, 'admin')");
  try {
    $insertStmt->bind_param("s", $username);
    $insertStmt->execute();

    $insertStmt = $conn->prepare("INSERT INTO admins (username, full_name, admin_photo, email, password,
                  short_self_introduction, 	long_self_introduction) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insertStmt->bind_param("sssssss", $username, $fullName, $photo, $email, $hashedPassword, $shortIntro, $longIntro);
    $insertStmt->send_long_data(2, $photo);

    if ($insertStmt->execute()) {
      $insertStmt->close();
      $conn->close();
      $_SESSION['success_message'] = "Admin added successfully.";
      $_SESSION['alter-username'] = $username ?? '';
      include '../server-side/add_new_user.php';
    } else {
      $insertStmt->close();
      $conn->close();
      $_SESSION['error_message'] = "Error adding admin: " . $conn->error;
      header("Location: /php-pages/client-side/display_error.php");
      exit;
    }
  } catch (Exception $e) {
    $_SESSION['error_message'] = "Error adding client: " . $e->getMessage();
    header("Location: /php-pages/client-side/display_error.php");
    $insertStmt->close();
    $conn->close();
    exit;
  }
  header("Location: /php-pages/admin-side/admin-dashboard.php");
  exit;
}

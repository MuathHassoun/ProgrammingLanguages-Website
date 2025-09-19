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
  $fullName = trim($_POST['full_name']);
  $email = trim($_POST['email']);
  $username = trim($_POST['add-username']);
  $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);

  if (empty($fullName) || empty($email) || empty($username) || empty($password)) {
    $_SESSION['error_message'] = "All fields are required.";
    header("Location: /php-pages/client-side/display_error.php");
    exit;
  }

  $insertStmt = $conn->prepare("INSERT INTO accounts (username, role) VALUES (?, 'user')");
  try {
    $insertStmt->bind_param("s", $username);
    $insertStmt->execute();

    $insertStmt = $conn->prepare("INSERT INTO users (username, full_name, email, password) VALUES (?, ?, ?, ?)");
    $insertStmt->bind_param("ssss", $username, $fullName, $email, $password);

    if ($insertStmt->execute()) {
      $insertStmt->close();
      $conn->close();
      $_SESSION['success_message'] = "Client added successfully.";
      $_SESSION['alter-username'] = $username ?? '';
      include '../server-side/add_new_user.php';
    } else {
      $insertStmt->close();
      $conn->close();
      $_SESSION['error_message'] = "Error adding client: " . $conn->error;
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

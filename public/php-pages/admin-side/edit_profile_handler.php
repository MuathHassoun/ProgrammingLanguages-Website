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
  $username = $_POST['username'] ?? '';
  $full_name = $_POST['full_name'] ?? '';
  $email = $_POST['email'] ?? '';
  $short_intro = $_POST['short_self_introduction'] ?? '';
  $long_intro = $_POST['long_self_introduction'] ?? '';

  $photoData = null;
  if (isset($_FILES['admin_photo']) && $_FILES['admin_photo']['error'] === UPLOAD_ERR_OK) {
    $photoData = file_get_contents($_FILES['admin_photo']['tmp_name']);
  }

  try {
    if ($photoData !== null) {
      $sql = "UPDATE admins SET full_name = ?, email = ?, short_self_introduction = ?, long_self_introduction = ?, admin_photo = ? WHERE username = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssssbs", $full_name, $email, $short_intro, $long_intro, $photoData, $username);
      $stmt->send_long_data(4, $photoData);
    } else {
      $sql = "UPDATE admins SET full_name = ?, email = ?, short_self_introduction = ?, long_self_introduction = ? WHERE username = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sssss", $full_name, $email, $short_intro, $long_intro, $username);
    }

    if ($stmt->execute()) {
      echo "Profile updated successfully.";
    } else {
      throw new Exception("Database update failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
  } catch (Exception $e) {
    http_response_code(500);
    $_SESSION['error_message'] = "An error occurred while updating the profile: " . $e->getMessage();
    header("Location: /php-pages/client-side/display_error.php");
    exit();
  }
}

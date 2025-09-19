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
  $admin_name    = $_SESSION['returned-full-name'];
  $username      = $_SESSION['username'];
  $id            = $_POST['reply-message-id'];
  $reply_content = $_POST['reply-content'];
  $to_name       = $_POST['reply-name'];
  $to_username   = $_POST['reply-to_username'];
  $subject       = $_POST['reply-subject'];

  $stmt = $conn->prepare("INSERT INTO reply_messages (from_admin, from_username, to_name, to_username, subject, reply_content) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssss", $admin_name, $username, $to_name, $to_username, $subject, $reply_content);
  try {
    $stmt->execute();
  } catch (Exception $e) {
    error_log("Failed to insert reply message with id: $id");
    $_SESSION['error_message'] = "Failed to insert a reply message with id: $id";
    header("Location: /php-pages/client-side/display_error.php");
    exit;
  } finally {
    $stmt->close();
    $conn->close();
  }
}

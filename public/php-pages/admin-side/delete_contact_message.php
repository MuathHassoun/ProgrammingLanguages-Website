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
  $id = $_POST['delete-contact-id'];
  $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
  $stmt->bind_param("i", $id);

  try {
    $stmt->execute();
    $stmt->close();
    $conn->close();
  } catch (Exception $e){
    $stmt->close();
    $conn->close();
    error_log("Failed to delete contact message with id: $id");
    $_SESSION['error_message'] = "Failed to delete contact message with id: $id";
    header("Location: /php-pages/client-side/display_error.php");
    exit;
  }
}

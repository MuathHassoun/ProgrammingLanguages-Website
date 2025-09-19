<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
  if(isset($_POST['set-unseen-username'])) {
    $conn = require(__DIR__ . '/../database-dir/connect.php');
    if (!$conn || $_SESSION['db_connected'] === false) {
      error_log("Database connection failed: " . $conn->connect_error);
      exit;
    }

    $username = $_POST['set-unseen-username'];
    $stmt = $conn->prepare("UPDATE reply_messages SET seen = 'seen' WHERE to_username = ?");
    $stmt->bind_param("s", $username);
    try {
      $stmt->execute();
    } catch (Exception $e) {
      error_log("Failed to set unseen messages for user: " . $e->getMessage());
      exit;
    } finally {
      $_SESSION['new_message_count'] = 0;
      $stmt->close();
      $conn->close();
    }
  }
}

<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true) {
  $conn = require(__DIR__ . '/../database-dir/connect.php');
  if (!$conn || $_SESSION['db_connected'] === false) {
    error_log("Database connection failed: " . $conn->connect_error);
    $_SESSION['error_message'] = "We encountered a technical issue while processing your request. Please try again later.";
    header("Location: /php-pages/client-side/display_error.php");
    exit;
  }

  $lastReplyDate = '';
  $lastReplyStmt = $conn->prepare("SELECT MAX(reply_date) as last_reply FROM reply_messages");
  if (!$lastReplyStmt) {
    error_log("Prepare failed for lastReplyStmt: " . $conn->error);
    exit;
  }

  $lastReplyStmt->execute();
  $lastReplyStmt->bind_result($lastReplyDate);
  $lastReplyStmt->fetch();
  $lastReplyStmt->close();
  if (!$lastReplyDate) {
    $lastReplyDate = '2025-01-01 00:00:00';
  }

  $messageCount = 0;
  $countStmt = $conn->prepare("SELECT COUNT(*) as message_count FROM contact_messages WHERE created_at > ?");
  $countStmt->bind_param("s", $lastReplyDate);
  $countStmt->execute();
  $countStmt->bind_result($messageCount);
  $countStmt->fetch();
  $countStmt->close();

  $_SESSION['has-notifications'] = $messageCount > 0;
  $_SESSION['new_message_count'] = $messageCount;

  $stmt = $conn->prepare("SELECT * FROM contact_messages WHERE created_at > ? ORDER BY created_at DESC");
  if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    $_SESSION['error_message'] = "Error fetching your messages.";
    header("Location: /php-pages/client-side/display_error.php");
    exit;
  }

  $stmt->bind_param("s", $lastReplyDate);
  try {
    $stmt->execute();
    $result = $stmt->get_result();
  } catch (Exception $e) {
    error_log("Failed to fetch messages: " . $e->getMessage());
    exit;
  }

  $contacts = [];
  while ($row = $result->fetch_assoc()) {
    $contacts[] = $row;
  }

  $stmt->close();
  $conn->close();
  $_SESSION['user-contacts'] = $contacts;
} elseif(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === false) {
  $conn = require(__DIR__ . '/../database-dir/connect.php');
  if (!$conn || $_SESSION['db_connected'] === false) {
    error_log("Database connection failed: " . $conn->connect_error);
    $_SESSION['error_message'] = "We encountered a technical issue while processing your request. Please try again later.";
    header("Location: /php-pages/client-side/display_error.php");
    exit;
  }

  $username = $_SESSION['username'];
  $stmt = $conn->prepare("SELECT * FROM reply_messages WHERE to_username = ? ORDER BY reply_date DESC");
  if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    $_SESSION['error_message'] = "Error fetching your messages.";
    header("Location: /php-pages/client-side/display_error.php");
    exit;
  }

  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  $replies = [];
  $counter = 0;
  while ($row = $result->fetch_assoc()) {
    $replies[] = $row;
    if($row['seen'] === 'unseen') {
      $counter++;
    }
  }
  $stmt->close();
  $conn->close();
  $_SESSION['user-replies'] = $replies;
  $_SESSION['has-notifications'] = isset($replies) && count($replies) > 0;
  $_SESSION['new_message_count'] = $counter;
}

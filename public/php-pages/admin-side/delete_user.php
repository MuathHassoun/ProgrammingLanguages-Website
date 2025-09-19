<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!isset($_POST['delete-username'])) {
    error_log("Database connection failed or returned false");
    $_SESSION['error_message'] = "Username was not provided.. Please try again later.";
    header("Location: /php-pages/client-side/display_error.php");
    exit;
  } elseif (!isset($_POST['role'])) {
    error_log("Database connection failed or returned false");
    $_SESSION['error_message'] = "Role was not provided.. Please try again later.";
    header("Location: /php-pages/client-side/display_error.php");
    exit;
  }

  $username = $_POST['delete-username'];
  $role = $_POST['role'];
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

  function safeDelete(mysqli $conn, string $query, string $param, string $username): void {
    try {
      $stmt = $conn->prepare($query);
      if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
      }

      $stmt->bind_param("s", $username);
      if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
      }
      $stmt->close();
    } catch (Exception $e) {
      $conn->close();
      error_log("Failed to delete from [$param]: " . $e->getMessage());
      $_SESSION['error_message'] = "Failed to delete user from [$param]: " . $e->getMessage();
      header("Location: /php-pages/client-side/display_error.php");
      exit;
    }
  }

  safeDelete($conn, "DELETE FROM languages WHERE username = ?", "languages", $username);
  safeDelete($conn, "DELETE FROM accounts WHERE username = ?", "accounts", $username);
  safeDelete($conn, "DELETE FROM contact_messages WHERE username = ?", "contact_messages", $username);
  if($role === 'admin') {
    safeDelete($conn, "DELETE FROM admins WHERE username = ?", "admins", $username);
  } elseif ($role === 'user') {
    safeDelete($conn, "DELETE FROM users WHERE username = ?", "users", $username);
  }
  $conn->close();

  $_SESSION['delete-flag'] = true;
  $_SESSION['callback_message-title'] = "User Deleted!";
  $_SESSION['callback_message-text'] = "The user ($username) has been successfully removed from the system.";
} else {
  $_SESSION['delete-flag'] = false;
  $_SESSION['callback_message-title'] = "User Not Found!";
  $_SESSION['callback_message-text'] = "No user with the username (" . $_POST['delete-username'] . ") was found. No changes were made.";
}
header("Location: /php-pages/admin-side/admin-dashboard.php");
exit;

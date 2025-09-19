<?php
require_once 'config.php';
try {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  $_SESSION['db_connected'] = false;
} catch (Exception $e) {
  error_log("Database connection failed: " . $e->getMessage());
  $_SESSION['error_message'] = "We encountered a technical issue while processing your request. Please try again later.";
  $_SESSION['db_connected'] = false;
  return;
}

if ($conn->connect_error) {
  $_SESSION['db_connected'] = false;
  error_log("Database connection failed: " . $conn->connect_error);
  $_SESSION['error_message'] = "We encountered a technical issue while processing your request. Please try again later.";
  header("Location: /php-pages/client-side/display_error.php");
  exit;
}

$_SESSION['db_connected'] = true;
return $conn;

<?php
  session_start();
  require_once 'config.php';
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  $_SESSION['db_connected'] = false;

  if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    $_SESSION['error_message'] = "We encountered a technical issue while processing your request. Please try again later.";
    header("Location: /php-pages/client-side/display_error.php");
    exit;
  }

  $_SESSION['db_connected'] = true;
  return $conn;

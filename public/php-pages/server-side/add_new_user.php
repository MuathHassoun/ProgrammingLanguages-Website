<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$sql = "
        INSERT INTO languages (
            username, language_name, language_logo, image_name, image_binary,
            definition, description, full_article, list_points,
            easy_to_learn, web_dev, mobile_dev, ai_ml, game_dev, performance,
            oop_supported, community_support, job_market_demand, syntax_simplicity,
            backend, frontend, documentation, video_embed, compiler_link, other_resources
        ) VALUES (
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?
        )
  ";

$conn = require(__DIR__ . '/../database-dir/connect.php');
if (!$conn || $_SESSION['db_connected'] === false) {
  error_log("Database connection failed: " . $conn->connect_error);
  $_SESSION['error_message'] = "We encountered a technical issue while processing your request. Please try again later.";
  header("Location: /php-pages/client-side/display_error.php");
  exit;
}

$stmt = $conn->prepare($sql);
if (!$stmt) {
  die("Prepare failed: " . $conn->error);
}

try {
  $save_username       = $_SESSION['username'];
  $save_logged_in      = $_SESSION['logged_in'];
  $save_new_user       = $_SESSION['new-user'];
  $save_isAdmin        = $_SESSION['isAdmin'];
  $save_alter_username = $_SESSION['alter-username'];

  unset($_SESSION['languages']);
  $_SESSION['languages'] = [];

  include 'prepare_initial_data.php';
  $_SESSION['username']       = $save_username;
  $_SESSION['alter-username'] = $save_alter_username;
  $_SESSION['logged_in']      = $save_logged_in;
  $_SESSION['new-user']       = $save_new_user;
  $_SESSION['isAdmin']        = $save_isAdmin;
} catch (Exception $e) {
  error_log("Insert failed: " . $e->getMessage());
  $_SESSION['error_message'] = "An error occurred while loading the languages data. Please try again later.";
  header("Location: /php-pages/client-side/display_error.php");
  exit;
}

try {
  foreach ($_SESSION['languages'] as $langKey => $lang) {
    $username        = $_SESSION['alter-username'];
    $language_name   = $lang['name'];
    $language_logo   = $lang['icon'];
    $image_name      = $lang['image-name'];
    $image_binary    = file_get_contents($lang['image']);

    $definition      = $lang['definition'];
    $description_json  = json_encode($lang['description'], JSON_UNESCAPED_UNICODE);
    $full_article_json = json_encode($lang['full_article'], JSON_UNESCAPED_UNICODE);
    $list_points_json  = json_encode($lang['list_points'], JSON_UNESCAPED_UNICODE);

    $features = $lang['features'];
    $easy_to_learn     = $features['Easy to Learn']     ?? '';
    $web_dev           = $features['Web Development']   ?? '';
    $mobile_dev        = $features['Mobile Development']?? '';
    $ai_ml             = $features['Used in AI / ML']   ?? '';
    $game_dev          = $features['Game Development']  ?? '';
    $performance       = $features['Performance']       ?? '';
    $oop_supported     = $features['Object-Oriented']   ?? '';
    $community_support = $features['Community Support'] ?? '';
    $job_market_demand = $features['Job Market Demand'] ?? '';
    $syntax_simplicity = $features['Syntax Simplicity'] ?? '';
    $backend           = $features['Backend Development']?? '';
    $frontend          = $features['Frontend Development']?? '';

    $documentation     = $lang['documentation'];
    $video_embed       = $lang['video_embed'];
    $compiler_link     = $lang['online_compiler'];
    $other_resources   = json_encode($lang['additional_resources'], JSON_UNESCAPED_UNICODE);

    $stmt->bind_param(
      "sssssssssssssssssssssssss",
      $username, $language_name, $language_logo, $image_name, $image_binary,
      $definition, $description_json, $full_article_json, $list_points_json,
      $easy_to_learn, $web_dev, $mobile_dev, $ai_ml, $game_dev, $performance,
      $oop_supported, $community_support, $job_market_demand, $syntax_simplicity,
      $backend, $frontend, $documentation, $video_embed, $compiler_link, $other_resources
    );
    $stmt->send_long_data(4, $image_binary);

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
      $stmt->execute();
    } catch (mysqli_sql_exception $e) {
      error_log("Insert failed for language '$language_name': " . $e->getMessage());
      $_SESSION['error_message'] = "An error occurred while saving the language '$language_name'. Please try again later.";
      header("Location: /php-pages/client-side/display_error.php");
      exit;
    }
  }
} catch (Exception $e) {
  error_log("Insert failed: " . $e->getMessage());
  $_SESSION['error_message'] = "An error occurred while loading the languages data. Please try again later.";
  header("Location: /php-pages/client-side/display_error.php");
  exit;
}

$stmt->close();
$conn->close();
$_SESSION['new-user'] = false;

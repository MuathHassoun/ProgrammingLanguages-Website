<?php
/**
 * @param mixed $raw_description
 * @return array|mixed|string|string[]
 */
function json_decode_after_reload(mixed $raw_description): mixed
{
  if ($decoded_desc = json_decode($raw_description, true)) {
    $description = $decoded_desc;
  } else {
    $description = stripslashes($raw_description);
    $description = trim($description, '"');
    $description = str_replace(["\\n", "\r\n"], "\n", $description);
    $description = str_replace('\\"', '"', $description);
  }
  return $description;
}

if (isset($_SESSION['new-user']) && $_SESSION['new-user'] === true) {
  header("Location: /php-pages/server-side/add_new_user.php");
} else {
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  $save_username = $_SESSION['username'];
  $save_logged_in = $_SESSION['logged_in'];
  $save_new_user = $_SESSION['new-user'];
  $save_isAdmin = $_SESSION['isAdmin'];

  unset($_SESSION['languages']);
  $_SESSION['languages'] = [];

  $username = $_SESSION['username'];
  $sql = "SELECT * FROM languages WHERE username = ?";

  $conn = require(__DIR__ . '/../database-dir/connect.php');
  if ($_SESSION['db_connected'] === false) {
    error_log("Database connection failed: " . $conn->connect_error);
    $_SESSION['error_message'] = "We encountered a technical issue while processing your request. Please try again later.";
    header("Location: /php-pages/client-side/display_error.php");
    exit;
  }

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    $imgPath = "data:image/jpeg;base64," . base64_encode($row['image_binary']);

    $features = [
      'Easy to Learn'        => $row['easy_to_learn'],
      'Web Development'      => $row['web_dev'],
      'Mobile Development'   => $row['mobile_dev'],
      'Game Development'     => $row['game_dev'],
      'Used in AI / ML'      => $row['ai_ml'],
      'Performance'          => $row['performance'],
      'Object-Oriented'      => $row['oop_supported'],
      'Community Support'    => $row['community_support'],
      'Job Market Demand'    => $row['job_market_demand'],
      'Syntax Simplicity'    => $row['syntax_simplicity'],
      'Backend Development'  => $row['backend'],
      'Frontend Development' => $row['frontend'],
    ];

    $additional_resources = json_decode($row['other_resources'], true) ?: [];
    $list_points = json_decode($row['list_points'], true);
    if (!is_array($list_points)) {
      $list_points = array_filter(array_map('trim', explode("\n", $row['list_points'])));
    }

    $raw_article = $row['full_article'];
    $full_article = json_decode_after_reload($raw_article);
    $raw_description = $row['description'];
    $description = json_decode_after_reload($raw_description);

    $key = strtolower($row['language_name']);
    $_SESSION['languages'][$key] = [
      'name'                 => $row['language_name'],
      'icon'                 => $row['language_logo'],
      'image-name'           => $row['image_name'],
      'image'                => $imgPath,
      'definition'           => $row['definition'],
      'features'             => $features,
      'description'          => $description,
      'full_article'         => $full_article,
      'list_points'          => $list_points,
      'documentation'        => $row['documentation'],
      'video_embed'          => $row['video_embed'],
      'online_compiler'      => $row['compiler_link'],
      'additional_resources' => $additional_resources,
    ];
  }

  $stmt->close();
  $conn->close();
  $_SESSION['username'] = $save_username;
  $_SESSION['logged_in'] = $save_logged_in;
  $_SESSION['new-user'] = $save_new_user;
  $_SESSION['isAdmin'] = $save_isAdmin;
}

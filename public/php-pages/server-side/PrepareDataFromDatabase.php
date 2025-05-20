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

//  $conn = new mysqli("localhost", "root", "", "progLangWebsite");
  $conn = new mysqli("sql209.infinityfree.com", "if0_39035367", "1pKEWmDL12VrMX", "if0_39035367_XXX");
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    die("Prepare failed: " . $conn->error);
  }

  try {
    $save_username = $_SESSION['username'];
    $save_logged_in = $_SESSION['logged_in'];
    $save_new_user = $_SESSION['new-user'];
    $save_isAdmin = $_SESSION['isAdmin'];

    unset($_SESSION['languages']);
    $_SESSION['languages'] = [];

    include 'PrepareInitialData.php';
    $_SESSION['username'] = $save_username;
    $_SESSION['logged_in'] = $save_logged_in;
    $_SESSION['new-user'] = $save_new_user;
    $_SESSION['isAdmin'] = $save_isAdmin;
  } catch (Exception $e) {
    error_log("Insert failed: " . $e->getMessage());
    $_SESSION['error_message'] = "An error occurred while loading the languages data. Please try again later.";
    header("Location: /php-pages/client-side/display_error.php");
    exit;
  }

  try {
    foreach ($_SESSION['languages'] as $langKey => $lang) {
      $username        = $_SESSION['username'];
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
  $_SESSION['new-user'] = false;
} else {
  $save_username = $_SESSION['username'];
  $save_logged_in = $_SESSION['logged_in'];
  $save_new_user = $_SESSION['new-user'];
  $save_isAdmin = $_SESSION['isAdmin'];

  unset($_SESSION['languages']);
  $_SESSION['languages'] = [];

  $username = $_SESSION['username'];
  $sql = "SELECT * FROM languages WHERE username = ?";

  $conn = new mysqli("sql209.infinityfree.com", "if0_39035367", "1pKEWmDL12VrMX", "if0_39035367_XXX");
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
  $_SESSION['username'] = $save_username;
  $_SESSION['logged_in'] = $save_logged_in;
  $_SESSION['new-user'] = $save_new_user;
  $_SESSION['isAdmin'] = $save_isAdmin;
}
$conn->close();

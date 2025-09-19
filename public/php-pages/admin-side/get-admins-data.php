<?php
  $conn = require(__DIR__ . '/../database-dir/connect.php');
  if ($_SESSION['db_connected'] === false || $conn->connect_error) {
    include_once(__DIR__ . '/../admin-side/admin-default-data.php');
    $_SESSION['admins-data'] = [];
    $_SESSION['admins-data']['muath-hassoun'] = [
      'admin-photo' => $_SESSION['admin-photo'],
      'full_name' => "Muath Hassoun",
      'short_self_introduction' => "A Computer Engineer with a deep passion for web development and modern technologies. Muath believes in the power of clean code and intuitive design to create interactive, user-friendly websites.",
      'long_self_introduction' => $_SESSION['long_self_introduction']
    ];
    return;
  }

  $sql = "SELECT username, full_name, admin_photo, short_self_introduction, long_self_introduction FROM admins";
  $result = $conn->query($sql);
  $_SESSION['admins-data'] = [];

  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $username = $row['username'];
      if (!empty($row['admin_photo'])) {
        $imgPath = "data:image/jpeg;base64," . base64_encode($row['admin_photo']);
      } else {
        $defaultImagePath = __DIR__ . '/../../img/Our/admin-photo.jpg';
        $defaultImageContent = file_exists($defaultImagePath) ? file_get_contents($defaultImagePath) : '';

        if (!empty($defaultImageContent)) {
          $imgPath = "data:image/jpeg;base64," . base64_encode($defaultImageContent);
          $updateStmt = $conn->prepare("UPDATE admins SET admin_photo = ? WHERE username = ?");
          $updateStmt->bind_param("ss", $defaultImageContent, $username);
          $updateStmt->send_long_data(0, $defaultImageContent);
          $updateStmt->execute();
          $updateStmt->close();
        } else {
          $imgPath = '';
        }
      }

      $_SESSION['admins-data'][$username] = [
        'admin-photo' => $imgPath,
        'returned-full-name' => $row['full_name'],
        'short_self_introduction' => $row['short_self_introduction'],
        'long_self_introduction' => $row['long_self_introduction']
      ];
    }
    $result->free();
  }
  $conn->close();

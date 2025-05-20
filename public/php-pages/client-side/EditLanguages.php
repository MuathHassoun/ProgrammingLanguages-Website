<?php
  use JetBrains\PhpStorm\NoReturn;
  session_start();
  if (isset($_GET['active'])) {
    $_SESSION['active-language-id'] = strtolower(trim($_GET['active']) ?? '');
  }

  #[NoReturn] function afterProcess(): void {
    $_SESSION['msg-from-edit-page'] = 'edit-languages-page';
    header("Location: ../../index.php");
    exit();
  }

  if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['login_msg'] = 'Please log in to access editing features.';
    $_SESSION['send_from'] = 'edit-languages-page';
    header("Location: Authentication.php");
    exit();
  } else {
    echo <<<HTML
          <script src="../../js/actions/authentication-js.js"></script>
          <script>
            document.addEventListener("DOMContentLoaded", function () {
              switchAuth();
              logout();
            });
          </script>
          HTML;
  }

  if (isset($_SESSION['successful_msg'])) {
    $msgParts = explode('|', $_SESSION['successful_msg'], 2);
    $title = htmlspecialchars($msgParts[0] ?? 'Success');
    $text = htmlspecialchars($msgParts[1] ?? '');
    unset($_SESSION['successful_msg']);

    echo <<<successAlert
            <script src="../../js/actions/sweet-alert2-js.js"></script>
            <script>
              document.addEventListener('DOMContentLoaded', () => {
                showAlert('success', '$title', '$text', '#0f1b30');
              });
            </script>
            successAlert;
  }

  /**
   * @param mixed $description
   * @param mixed $full_article
   * @param mixed $list_points
   * @return array|void
   */
  function convertToJSON(mixed $description, mixed $full_article, mixed $list_points) {
    $json_description = json_encode($description, JSON_UNESCAPED_UNICODE);
    $json_full_article = json_encode($full_article, JSON_UNESCAPED_UNICODE);
    $json_list_points = json_encode($list_points, JSON_UNESCAPED_UNICODE);

    if (json_last_error() !== JSON_ERROR_NONE) {
      http_response_code(500);
      $errorMessage = "Error encoding data to JSON: " . json_last_error_msg() .
        ". Please make sure the input is correctly formatted:\n" .
        "- Description and Full Article should be plain text (example: C is a powerful language).\n" .
        "- List Points should be a valid JSON array (example: \"Fast: C is a powerful language\").";
      $error = urlencode($errorMessage);
      header("Location: display_error.php?error=$error");
      exit;
    }
    return array($json_description, $json_full_article, $json_list_points);
  }

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_SESSION['username'];
    if(isset($_POST['addNewBtn'])){
      $languageName      = $_POST['languageName'];
      $languageLogo      = $_POST['languageLogo'];
      $languageImage     = $_FILES['languageImage'];
      $definition        = $_POST['definition'];
      $description       = $_POST['description'];
      $full_article      = $_POST['full_article'];
      $list_points       = $_POST['list_points'];
      $easyToLearn       = $_POST['easyToLearn'];
      $webDev            = $_POST['webDev'];
      $mobileDev         = $_POST['mobileDev'];
      $gameDev           = $_POST['gameDev'];
      $aiMl              = $_POST['aiMl'];
      $performance       = $_POST['performance'];
      $objectOriented    = $_POST['objectOriented'];
      $communitySupport  = $_POST['communitySupport'];
      $marketDemand      = $_POST['marketDemand'];
      $syntaxSimplicity  = $_POST['syntaxSimplicity'];
      $backendDev        = $_POST['backendDev'];
      $frontendDev       = $_POST['frontendDev'];
      $documentation     = $_POST['docLink'];
      $video_embed       = $_POST['videoEmbed'];
      $compilerUrl       = $_POST['compilerUrl'];

      $_FILES['image-file-to-check'] = $languageImage;
      include '../server-side/check_uploaded_file.php';
      if (isset($_SESSION['image_valid']) && $_SESSION['image_valid'] === false) {
        $_SESSION['error_message'] = "There was a problem with the uploaded image. Please try again.";
        unset($_SESSION['image_valid']);
        header("Location: display_error.php");
        exit;
      }

      list($json_description, $json_full_article, $json_list_points) = convertToJSON($description, $full_article, $list_points);
      if ($languageImage === null) {
        $_SESSION['error_message'] = "Undefined array key 'languageImage' at EditLanguages.php line 44";
        header("Location: display_error.php");
        exit;
      }

      if($languageName && $languageLogo && $languageImage && $definition &&
        $json_description && $full_article && $list_points && $easyToLearn &&
        $webDev && $mobileDev && $gameDev && $aiMl && $performance &&
        $objectOriented && $communitySupport && $marketDemand && $syntaxSimplicity
        && $backendDev && $frontendDev && $documentation && $video_embed && $compilerUrl) {

        if (!isset($_FILES['languageImage']) || $_FILES['languageImage']['error'] !== UPLOAD_ERR_OK) {
          $_SESSION['error_message'] = "Please upload a valid image file.";
          header("Location: display_error.php");
          exit;
        }

        $otherResources    = $_POST['otherResources'];
        $tmpPath           = $_FILES['languageImage']['tmp_name'];
        $imageName         = $_FILES['languageImage']['name'];
        $languageImageData = getImage($tmpPath, $imageName);

//        $conn = require_once '../database-dir/connect.php';
//        if ($_SESSION['db_connected'] === false) {
//          error_log("Database connection failed: " . $conn->connect_error);
//          $_SESSION['error_message'] = "We encountered a technical issue while processing your request. Please try again later.";
//          header("Location: /php-pages/client-side/display_error.php");
//          exit;
//        }
        $conn = new mysqli("localhost", "root", "", "progLangWebsite");
        if($conn->connect_error) {
          error_log("Database connection failed: " . $conn->connect_error);
          $_SESSION['error_message'] = "We encountered a technical issue while processing your request. Please try again later.";
          header("Location: /php-pages/client-side/display_error.php");
          exit;
        }

        $stmt = $conn->prepare("
            INSERT INTO languages (
                username, language_name, language_logo, image_name, image_binary, definition, description,
                full_article, list_points, easy_to_learn, web_dev, mobile_dev, ai_ml, game_dev,
                performance, oop_supported, community_support, job_market_demand, syntax_simplicity,
                backend, frontend, documentation, video_embed, compiler_link, other_resources
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");

        $stmt->bind_param(
          "sssssssssssssssssssssssss",
          $username, $languageName, $languageLogo, $imageName, $languageImageData, $definition,
          $json_description, $json_full_article, $json_list_points, $easyToLearn, $webDev, $mobileDev, $aiMl,
          $gameDev, $performance, $objectOriented, $communitySupport, $marketDemand, $syntaxSimplicity,
          $backendDev, $frontendDev, $documentation, $video_embed, $compilerUrl, $otherResources
        );
        $stmt->send_long_data(4, $languageImageData);

        try {
          if ($stmt->execute()) {
            $_SESSION['status'] = "success";
            $_SESSION['msg_title'] = "Language Added!";
            $_SESSION['message'] = "The new language has been added successfully.";
          } else {
            $_SESSION['status'] = "error";
            $_SESSION['msg_title'] = "Insert Failed!";
            $_SESSION['message'] = "Failed to add the language: '" . htmlspecialchars($stmt->error) . "'.";
          }
          $stmt->close();
          $conn->close();
          afterProcess();
        } catch (Exception $e) {
          $stmt->close();
          $conn->close();

          error_log("Insert failed: " . $e->getMessage());
          $_SESSION['error_message'] = "An error occurred while saving the language data. Please try again later.\n" . $e->getMessage();
          header("Location: /php-pages/client-side/display_error.php");
          exit;
        }
      }
    } elseif(isset($_POST['editBtn'])) {
      if (isset($_FILES['editLanguageImage']) && $_FILES['editLanguageImage']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['editLanguageImage'];
        $_FILES['image-file-to-check'] = $image;
        include '../server-side/check_uploaded_file.php';
        if (isset($_SESSION['image_valid']) && $_SESSION['image_valid'] === false) {
          $_SESSION['error_message'] = "There was a problem with the uploaded image. Please try again.";
          unset($_SESSION['image_valid']);
          header("Location: display_error.php");
          exit;
        }
      }

      include '../server-side/save_to_session.php';
      $languageName       = $_SESSION['editLanguageName'];
      $languageLogo       = $_SESSION['editLanguageLogo'];
      $imageName          = $_SESSION['editImageName'];
      $languageImage      = $_SESSION['editLanguageImage'];
      $definition         = $_SESSION['editDefinition'];
      $description        = $_SESSION['editDescription'];
      $full_article       = $_SESSION['edit_full_article'];
      $list_points        = $_SESSION['edit_list_points'];
      $easyToLearn        = $_SESSION['editEasyToLearn'];
      $webDev             = $_SESSION['editWebDev'];
      $mobileDev          = $_SESSION['editMobileDev'];
      $gameDev            = $_SESSION['editGameDev'];
      $aiMl               = $_SESSION['editAiMl'];
      $performance        = $_SESSION['editPerformance'];
      $objectOriented     = $_SESSION['editObjectOriented'];
      $communitySupport   = $_SESSION['editCommunitySupport'];
      $marketDemand       = $_SESSION['editMarketDemand'];
      $syntaxSimplicity   = $_SESSION['editSyntaxSimplicity'];
      $backendDev         = $_SESSION['editBackendDev'];
      $frontendDev        = $_SESSION['editFrontendDev'];
      $documentation      = $_SESSION['edit-documentation'];
      $video_embed        = $_SESSION['editVideoEmbed'];
      $compilerUrl        = $_SESSION['editCompilerUrl'];
      $otherResourcesEdit = $_SESSION['otherResourcesEdit'];

      list($json_description, $json_full_article, $json_list_points) = convertToJSON($description, $full_article, $list_points);
      if($languageName && $languageLogo && $imageName && $languageImage && $definition &&
        $json_description && $json_full_article && $json_list_points && $easyToLearn &&
        $webDev && $mobileDev && $gameDev && $aiMl && $performance &&
        $objectOriented && $communitySupport && $marketDemand && $syntaxSimplicity
        && $backendDev && $frontendDev && $documentation && $video_embed && $compilerUrl) {

//        $conn = require_once '../database-dir/connect.php';
//        if ($_SESSION['db_connected'] === false) {
//          error_log("Database connection failed: " . $conn->connect_error);
//          $_SESSION['error_message'] = "We encountered a technical issue while processing your request. Please try again later.";
//          header("Location: /php-pages/client-side/display_error.php");
//          exit;
//        }
        $conn = new mysqli("localhost", "root", "", "progLangWebsite");
        if($conn->connect_error) {
          error_log("Database connection failed: " . $conn->connect_error);
          $_SESSION['error_message'] = "We encountered a technical issue while processing your request. Please try again later.";
          header("Location: /php-pages/client-side/display_error.php");
          exit;
        }

        $stmt = $conn->prepare("
            UPDATE languages SET
                language_logo = ?, image_name = ?, image_binary = ?, definition = ?, description = ?, full_article = ?,
                list_points = ?, easy_to_learn = ?, web_dev = ?, mobile_dev = ?, ai_ml = ?, game_dev = ?,
                performance = ?, oop_supported = ?, community_support = ?, job_market_demand = ?, syntax_simplicity = ?,
                backend = ?, frontend = ?, documentation = ?, video_embed = ?, compiler_link = ?, other_resources = ?
            WHERE username = ? AND language_name = ?
        ");

        $stmt->bind_param(
          "sssssssssssssssssssssssss",
          $languageLogo, $imageName, $languageImage, $definition, $json_description,
          $json_full_article, $json_list_points, $easyToLearn, $webDev, $mobileDev, $aiMl,
          $gameDev, $performance, $objectOriented, $communitySupport, $marketDemand,
          $syntaxSimplicity, $backendDev, $frontendDev, $documentation, $video_embed, $compilerUrl,
          $otherResourcesEdit, $username, $languageName
        );
        $stmt->send_long_data(2, $languageImage);

        try {
          if ($stmt->execute()) {
            $_SESSION['status'] = "success";
            $_SESSION['msg_title'] = "Update Successful!";
            $_SESSION['message'] = "The language data has been updated successfully.";
          } else {
            $_SESSION['status'] = "error";
            $_SESSION['msg_title'] = "Update Failed!";
            $_SESSION['message'] = "Failed to update the language: '" . htmlspecialchars($stmt->error) . "'.";
          }
          echo 'end';
          $stmt->close();
          $conn->close();
          include '../server-side/clear_edit_session.php';
          afterProcess();
        } catch (Exception $e) {
          $stmt->close();
          $conn->close();
          error_log("Insert failed: " . $e->getMessage());
          $_SESSION['error_message'] = "An error occurred while saving the language data. Please try again later.\n" . $e->getMessage();
          header("Location: /php-pages/client-side/display_error.php");
          exit;
        }
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../css/style.css" />
  <link rel="stylesheet" href="../../css/embellishment-style.css" />
  <link rel="stylesheet" href="../../css/waves-style.css" />
  <link rel="stylesheet" href="../../css/contact-style.css" />
  <link rel="stylesheet" href="../../css/edit-new-style.css" />
  <link rel="stylesheet" href="../../css/languages-style.css" />
  <link rel="stylesheet" href="../../css/toggle-sidebar.css" />
  <link rel="icon" href="../../img/icon/icon.png" type="image/x-icon" />
  <script type="module" src="../../js/actions/edit-new-js.js"></script>
  <title>Edit/Add Languages</title>
</head>
<body>
  <!-- Header -->
  <header>
    <div class="logo">
      <a href="../../index.php" class="logo-link">
        <img class="logo-icon" src="../../img/icon/icon.png" alt="CodeWorld Logo" />
        <h1 class="website-title">CodeWorld!</h1>
      </a>
      <a href="../../index.php#learn-section" class="section-subtitle-link">
        <h1 class="section-subtitle">Learn Programming Languages</h1>
      </a>

      <section class="x-container">
        <section class="x-dimension">
          <div class="xcircle" style="--i:0;"></div>
          <div class="xcircle" style="--i:1;"></div>
          <div class="xcircle" style="--i:2;"></div>
          <div class="xcircle" style="--i:3;"></div>
          <div class="xcircle" style="--i:4;"></div>
          <div class="xcircle" style="--i:5;"></div>
          <div class="xcircle" style="--i:6;"></div>
          <div class="xcircle" style="--i:7;"></div>
          <div class="xcircle" style="--i:8;"></div>
          <div class="xcircle" style="--i:9;"></div>
          <div class="xcircle" style="--i:10;"></div>
        </section>
      </section>
    </div>

    <nav>
      <div class="nav-center">
        <a href="../../index.php">Home</a>
        <a href="Languages.php#languages-section">Learn Languages</a>
        <a href="Documentation.php#doc-section">Documentation</a>
        <a href="Compare.php#compare-section">Compare</a>
        <a href="AboutUs.php#about-us-section">About Us</a>
        <a href="ContactUs.php#contact-us-section">Contact Us</a>
      </div>
      <div class="nav-auth">
        <a href="Authentication.php#authentication-section" class="not-active-user">Authentication</a>
        <a href="Logout.php" class="logout-not-active">Logout</a>
      </div>
    </nav>
  </header>

  <main class="edit-new" id="edit-section">
    <button id="toggleSidebar" class="toggle-sidebar-btn">☰</button>
    <div class="sidebar">
      <button class="sidebar-btn" onclick="showEditSettings('addLanguageForm', 'new')"> ➕ New </button>
      <script src="../../js/actions/edit-new-js.js"></script>
      <?php
        foreach ($_SESSION["languages"] as $languageData) {
          $languageName = htmlspecialchars($languageData['name'], ENT_QUOTES);
          echo '<button class="sidebar-btn" onclick="showEditSettings(\'editLanguageForm\', \'' . $languageName . '\')">' . $languageName . '</button>';
        }
      ?>
    </div>

    <div class="edit-new-container">
      <div id="addLanguageForm" class="language-form" style="display: block;">
        <h2>🧾 Add a New Programming Language</h2>
        <hr>
        <br><br>
        <form method="post" enctype="multipart/form-data"
              onsubmit="setupGetResources('resourceText[]', 'resourceURL[]', 'otherResourcesInput')">
          <table class="language-table">
            <tr>
              <td class="field-label"><label for="languageNameInput">Language Name:</label></td>
              <td>
                <input type="text" name="languageName" id="languageNameInput" class="language-input" required />
              </td>
            </tr>
            <tr>
              <td class="field-label"><label for="languageLogoInput">Language Logo:</label></td>
              <td>
                <input type="text" minlength="1" maxlength="2" name="languageLogo" id="languageLogoInput" class="language-logo" placeholder="🐘" required />
              </td>
            </tr>
            <tr>
              <td class="field-label"><label for="languageImageInput">Language Image:</label></td>
              <td>
                <input type="file" name="languageImage" id="languageImageInput" class="language-url" required />
              </td>
            </tr>
            <tr>
              <td class="field-label"><label for="definitionInput">📖 Definition:</label></td>
              <td>
                <input type="text" minlength="40" maxlength="80" name="definition" id="definitionInput"
                       placeholder="Enter a definition (40–80 characters)" class="language-definition" required>
              </td>
            </tr>
            <tr>
              <td class="field-label"><label for="descriptionInput">📌 Description:</label></td>
              <td>
                <textarea name="description" id="descriptionInput" class="language-textarea" required></textarea>
              </td>
            </tr>
            <tr>
              <td class="field-label"><label for="fullArticleInput"><i class="fas fa-file-lines"></i> Full Article:</label></td>
              <td>
                <textarea name="full_article" id="fullArticleInput" class="language-textarea largest-textarea" required></textarea>
              </td>
            </tr>
            <tr>
              <td class="field-label"><label for="listPointsInput"><i class="fas fa-list"></i> List of Points:</label></td>
              <td>
                <textarea name="list_points" id="listPointsInput" class="language-textarea largest-textarea" required></textarea>
              </td>
            </tr>
            <tr>
              <td class="field-label">📊 Feature Ratings:</td>
              <td>
                <p>Select the rating for each feature (✅ Excellent, 🟩 Good, 🔶 Partial, ❌ Not Suitable):</p>
                <br>
                <?php
                  $features = [
                    'easyToLearn' => ['id' => 'newEasySelect', 'label' => 'Easy to Learn'],
                    'webDev' => ['id' => 'newWebDevSelect', 'label' => 'Web Development'],
                    'mobileDev' => ['id' => 'newMobileDevSelect', 'label' => 'Mobile Development'],
                    'gameDev' => ['id' => 'newGameDevSelect', 'label' => 'Game Development'],
                    'aiMl' => ['id' => 'newAiMlSelect', 'label' => 'Used in AI / ML'],
                    'performance' => ['id' => 'newPerformanceSelect', 'label' => 'Performance'],
                    'objectOriented' => ['id' => 'newOopSelect', 'label' => 'Object-Oriented'],
                    'communitySupport' => ['id' => 'newCommunitySelect', 'label' => 'Community Support'],
                    'marketDemand' => ['id' => 'newMarketDemandSelect', 'label' => 'Job Market Demand'],
                    'syntaxSimplicity' => ['id' => 'newSyntaxSelect', 'label' => 'Syntax Simplicity'],
                    'backendDev' => ['id' => 'newBackendSelect', 'label' => 'Backend Development'],
                    'frontendDev' => ['id' => 'newFrontendSelect', 'label' => 'Frontend Development']
                  ];

                  $options = ['✅', '🟩', '🔶', '❌'];

                  foreach ($features as $name => $info) {
                    echo '<div class="edit-language-ratings">';
                    echo '<label for="' . htmlspecialchars($info['id']) . '" class="rate-label">' . htmlspecialchars($info['label']) . ':</label>';
                    echo '<select name="' . htmlspecialchars($name) . '" id="' . htmlspecialchars($info['id']) . '" class="language-select" required>';
                    foreach ($options as $option) {
                      echo '<option value="' . $option . '">' . $option . '</option>';
                    }
                    echo '</select></div>';
                  }
                ?>
              </td>
            </tr>
            <tr>
              <td class="field-label data-box" colspan="2">
                <h3>🔗 Learning Resources:</h3>
                <br><br>
                <section class="learning-resources">
                  <div class="add-new-learning-resources">
                    <label for="docLinkInput">Official Documentation URL:</label>
                    <input type="url" name="docLink" id="docLinkInput" class="language-url" required/>
                  </div>
                  <div class="add-new-learning-resources">
                    <label for="videoEmbedLinkInput">Course/Tutorial Video Link:</label>
                    <input type="url" name="videoEmbed" id="videoEmbedLinkInput" class="language-url" required/>
                  </div>
                  <div class="add-new-learning-resources">
                    <label for="compilerUrlInput" class="title-h3">🧪 Compiler URL:</label>
                    <input type="url" name="compilerUrl" id="compilerUrlInput" class="language-url" required/>
                  </div>
                </section>
              </td>
            </tr>
            <tr>
              <td class="field-label" colspan="2">
                <div id="resourcesContainer"></div>
                <input type="hidden" id="otherResourcesInput" name="otherResources">
              </td>
            </tr>
            <tr>
              <td class="field-label submit-btn resource-button" colspan="2">
                <input type="button" name="removeRow" id="removeRow" class="row-control" value="➖ Remove Last Resource" />
                <input type="button" name="addNewRow" id="addNewRow" class="row-control" value="➕ Add Resource" />
                <button type="submit" name="addNewBtn" id="submitLanguageBtn">Submit Language</button>
              </td>
            </tr>
          </table>
        </form>
      </div>

      <div id="editLanguageForm" class="language-form" style="display: none;">
        <h2>🧾 Edit an Available Programming Language</h2>
        <hr>
        <br><br>
        <form method="post" enctype="multipart/form-data"
              onsubmit="setupGetResources('resourceTextEdit[]', 'resourceURLEdit[]', 'otherResourcesInputEdit')">
          <table class="language-table">
            <tr>
              <td class="field-label"><label for="editLanguageNameInput">Language Name:</label></td>
              <td>
                <?php
                  $languageKey = $_SESSION['active-language-id'] ?? '';
                  if ($languageKey && isset($_SESSION['languages'][$languageKey])) {
                    $langName = htmlspecialchars($_SESSION['languages'][$languageKey]['name']);
                  } else {
                    $langName = '';
                  }
                  echo '<input type="text" name="editLanguageName" id="languageNameInput" class="language-input"
                        value="' . $langName . '" required readonly/>';
                ?>
              </td>
            </tr>
            <tr>
              <td class="field-label"><label for="editLanguageLogoInput">Language Logo:</label></td>
              <td>
                <?php
                  $languageKey = $_SESSION['active-language-id'] ?? '';
                  if ($languageKey && isset($_SESSION['languages'][$languageKey])) {
                    $langIcon = htmlspecialchars($_SESSION['languages'][$languageKey]['icon']);
                  } else {
                    $langIcon = '';
                  }
                  echo '<input type="text" minlength="1" maxlength="2" name="editLanguageLogo" id="editLanguageLogoInput"
                         value="'. $langIcon .'" class="language-logo" placeholder="🐘" required />';
                ?>
              </td>
            </tr>
            <tr>
              <td class="field-label"><label for="editLanguageImageInput">Language Image:</label></td>
              <td>
                <?php
                  $languageKey = $_SESSION['active-language-id'] ?? '';
                  if ($languageKey && isset($_SESSION['languages'][$languageKey])) {
                    $langImage = htmlspecialchars($_SESSION['languages'][$languageKey]['image-name']);
                  } else {
                    $langImage = '';
                  }

                  echo '<input type="file" name="editLanguageImage" id="editLanguageImageInput" class="language-url" />';
                  if ($langImage) {
                    echo '<span id="oldImageName" class="current-image-label">Current Image: ' . $langImage . '</span>';
                  }
                ?>
              </td>
            </tr>
            <tr>
              <td class="field-label"><label for="editDefinitionInput">📖 Definition:</label></td>
              <td>
                <?php
                  $languageKey = $_SESSION['active-language-id'] ?? '';
                  if ($languageKey && isset($_SESSION['languages'][$languageKey])) {
                    $langDefinition = htmlspecialchars($_SESSION['languages'][$languageKey]['definition']);
                  } else {
                    $langDefinition = '';
                  }
                  echo '<input type="text" minlength="40" maxlength="80" name="editDefinition" id="editDefinitionInput"
                         value="'. $langDefinition .'" placeholder="Enter a definition (40–80 characters)" class="language-definition" required>';
                ?>
              </td>
            </tr>
            <tr>
              <td class="field-label"><label for="editDescriptionInput">📌 Description:</label></td>
              <td>
                <?php
                  $description = htmlspecialchars($_SESSION['languages'][$_SESSION['active-language-id']]['description'] ?? '');
                  echo '<textarea name="editDescription" id="editDescriptionInput" class="language-textarea" required>'
                    . $description .
                    '</textarea>';
                ?>
              </td>
            </tr>
            <tr>
              <td class="field-label"><label for="editFullArticleInput"><i class="fas fa-file-lines"></i> Full Article:</label></td>
              <td>
                <?php
                  $fullArticle = htmlspecialchars($_SESSION['languages'][$_SESSION['active-language-id']]['full_article'] ?? '');
                  echo '<textarea name="edit_full_article" id="editFullArticleInput" class="language-textarea largest-textarea" required>'
                    . $fullArticle .
                    '</textarea>';
                ?>
              </td>
            </tr>
            <tr>
              <td class="field-label"><label for="editListPointsInput"><i class="fas fa-list"></i> List of Points:</label></td>
              <td>
                <?php
                  $key = $_SESSION['active-language-id'] ?? '';
                  $pointsArray = [];
                  if ($key && isset($_SESSION['languages'][$key]['list_points'])) {
                    $pointsArray = $_SESSION['languages'][$key]['list_points'];
                  }

                  $listPointsText = implode("\n", $pointsArray);
                  echo '<textarea name="edit_list_points" '
                    . 'id="editListPointsInput" '
                    . 'class="language-textarea largest-textarea" '
                    . 'required>'
                    . htmlspecialchars($listPointsText)
                    . '</textarea>';
                ?>
              </td>
            </tr>
            <tr>
              <td class="field-label">📊 Feature Ratings:</td>
              <td>
                <p>Select the rating for each feature (✅ Excellent, 🟩 Good, 🔶 Partial, ❌ Not Suitable):</p>
                <br>
                <?php
                  $features = [
                    'Easy to Learn' => ['id' => 'editEasySelect', 'name' => 'editEasyToLearn', 'label' => 'Easy to Learn'],
                    'Web Development' => ['id' => 'editWebDevSelect', 'name' => 'editWebDev', 'label' => 'Web Development'],
                    'Mobile Development' => ['id' => 'editMobileDevSelect', 'name' => 'editMobileDev', 'label' => 'Mobile Development'],
                    'Game Development' => ['id' => 'editGameDevSelect', 'name' => 'editGameDev', 'label' => 'Game Development'],
                    'Used in AI / ML' => ['id' => 'editAiMlSelect', 'name' => 'editAiMl', 'label' => 'Used in AI / ML'],
                    'Performance' => ['id' => 'editPerformanceSelect', 'name' => 'editPerformance', 'label' => 'Performance'],
                    'Object-Oriented' => ['id' => 'editOopSelect', 'name' => 'editObjectOriented', 'label' => 'Object-Oriented'],
                    'Community Support' => ['id' => 'editCommunitySelect', 'name' => 'editCommunitySupport', 'label' => 'Community Support'],
                    'Job Market Demand' => ['id' => 'editMarketDemandSelect', 'name' => 'editMarketDemand', 'label' => 'Job Market Demand'],
                    'Syntax Simplicity' => ['id' => 'editSyntaxSelect', 'name' => 'editSyntaxSimplicity', 'label' => 'Syntax Simplicity'],
                    'Backend Development' => ['id' => 'editBackendSelect', 'name' => 'editBackendDev', 'label' => 'Backend Development'],
                    'Frontend Development' => ['id' => 'editFrontendSelect', 'name' => 'editFrontendDev', 'label' => 'Frontend Development']
                  ];

                  $options = ['✅', '🟩', '🔶', '❌'];
                  $languageFeatures = $_SESSION['languages'][$_SESSION['active-language-id']]['features'] ?? [];

                  foreach ($features as $featureKey => $info) {
                    $value = $languageFeatures[$featureKey] ?? '';
                    echo '<div class="edit-language-ratings">';
                    echo '<label for="' . htmlspecialchars($info['id']) . '" class="rate-label">' . htmlspecialchars($info['label']) . ':</label>';
                    echo '<select name="' . htmlspecialchars($info['name']) . '" id="' . htmlspecialchars($info['id']) . '" class="language-select">';
                    foreach ($options as $option) {
                      $selected = htmlspecialchars($value) === $option ? 'selected' : '';
                      echo "<option value=\"$option\" $selected>$option</option>";
                    }
                    echo '</select></div>';
                  }
                ?>
              </td>
            </tr>
            <tr>
              <td class="field-label data-box" colspan="2">
                <h3>🔗 Learning Resources:</h3>
                <br><br>
                <section class="learning-resources">
                  <div class="edit-learning-resources">
                    <label for="editDocLinkInput">Official Documentation URL:</label>
                    <?php
                      $languageKey = $_SESSION['active-language-id'] ?? '';
                      if ($languageKey && isset($_SESSION['languages'][$languageKey])) {
                        $docLink = htmlspecialchars($_SESSION['languages'][$languageKey]['documentation']);
                        $docLink = '';
                      } else {
                        $docLink = '';
                      }
                      echo '<input type="url" name="editDocLink" id="editDocLinkInput" class="language-url" value="' . $docLink . '" />';
                    ?>
                  </div>
                  <div class="edit-learning-resources">
                    <label for="editVideoEmbedInput">Course/Tutorial Video Link:</label>
                    <?php
                      $languageKey = $_SESSION['active-language-id'] ?? '';
                      if ($languageKey && isset($_SESSION['languages'][$languageKey])) {
                        $videoEmbed = htmlspecialchars($_SESSION['languages'][$languageKey]['video_embed']);
                      } else {
                        $videoEmbed = '';
                      }
                      echo '<input type="url" name="editVideoEmbed" id="editVideoEmbedInput" class="language-url" value="' . $videoEmbed . '" />';
                    ?>
                  </div>
                  <div class="edit-learning-resources">
                    <label for="editCompilerUrlInput" class="title-h3">🧪 Compiler URL:</label>
                    <?php
                      $languageKey = $_SESSION['active-language-id'] ?? '';
                      if ($languageKey && isset($_SESSION['languages'][$languageKey])) {
                        $compilerUrl = htmlspecialchars($_SESSION['languages'][$languageKey]['online_compiler']);
                      } else {
                        $compilerUrl = '';
                      }
                      echo '<input type="url" name="editCompilerUrl" id="editCompilerUrlInput" class="language-url" value="' . $compilerUrl . '" />';
                    ?>
                  </div>
                </section>
              </td>
            </tr>
            <tr>
              <td class="field-label" colspan="2">
                <div id="resourcesContainer-Edit">
                  <?php
                    function createResourceRow($count, $textName, $urlName): void {
                      echo '<div class="resource-div" style="display: flex; flex-direction: row; gap: 1rem; margin-bottom: 1rem;">';
                      echo '<input type="text" ';
                      echo 'name="resourceTextEdit[]"';
                      echo 'id="' . htmlspecialchars($textName) . '-' . $count . '" ';
                      echo 'placeholder="📚 Resource Title" ';
                      echo 'required ';
                      echo 'class="language-textarea" ';
                      echo 'value="' . $textName . '"';
                      echo 'style="width: 35%; height: 2.2rem; padding: 0.5rem;">';

                      echo '<input type="url" ';
                      echo 'name="resourceURLEdit[]"';
                      echo 'id="' . htmlspecialchars($urlName) . '-' . $count . '" ';
                      echo 'placeholder="🔗 Resource URL" ';
                      echo 'required ';
                      echo 'class="language-textarea" ';
                      echo 'value="' . $urlName . '"';
                      echo 'style="width: 65%; height: 2.2rem; padding: 0.5rem;">';
                      echo '</div>';
                    }

                    $languageKey = $_SESSION['active-language-id'] ?? '';
                    if ($languageKey && isset($_SESSION['languages'][$languageKey])) {
                      $resources = $_SESSION['languages'][$languageKey]['additional_resources'] ?? [];
                    } else {
                      $resources = [];
                    }
                    $i = 0;
                    foreach ($resources as $resource) {
                      createResourceRow($i, $resource['title'], $resource['link']);
                    }
                  ?>
                </div>
                <input type="hidden" id="otherResourcesInputEdit" name="otherResourcesEdit">
              </td>
            </tr>
            <tr>
              <td class="field-label submit-btn resource-button" colspan="2">
                <input type="button" name="removeRow" id="removeRow-Edit" class="row-control" value="➖ Remove Last Resource" />
                <input type="button" name="addNewRow" id="addNewRow-Edit" class="row-control" value="➕ Add Resource" />
                <button type="submit" name="editBtn" id="editSubmitLanguageBtn">Submit Language</button>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </main>

  <footer>
    <p>© 2025 Learn Programming Languages - All Rights Reserved</p>
  </footer>
  <div class="waves"></div>
  <script src="../../js/actions/edit-new-js.js"></script>
  <script src="../../js/actions/toggle-sidebar-js.js"></script>
  <script>
    window.addEventListener('DOMContentLoaded', () => {
      const active = "<?php echo $_GET['active'] ?? ''; ?>";
      const blockId = "<?php echo $_GET['block'] ?? ''; ?>";
      if (active && blockId) {
        sessionStorage.setItem('active-language-id', active);
        sessionStorage.setItem('active-language-block-id', blockId);
      }

      const savedBlockId = sessionStorage.getItem('active-language-block-id');
      const savedLanguageId = sessionStorage.getItem('active-language-id');
      if (savedBlockId && savedLanguageId) {
        showEditSettings(savedBlockId, savedLanguageId);
      }
    });
  </script>
</body>
</html>

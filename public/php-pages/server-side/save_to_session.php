<?php
/**
 * @param mixed $tmpPath
 * @param mixed $imageName
 * @return string|void
 */
function getImage(mixed $tmpPath, mixed $imageName) {
  $binaryImg = file_get_contents($tmpPath);
  if ($binaryImg === false) {
    http_response_code(500);
    $errorMessage = "Could not read the temporary upload image.";
    $error = urlencode($errorMessage);
    header("Location: /php-pages/client-side/display_error.php?error=$error");
    exit;
  } elseif ($imageName === null) {
    http_response_code(500);
    $errorMessage = "Could not read the temporary upload image.";
    $error = urlencode($errorMessage);
    header("Location: /php-pages/client-side/display_error.php?error=$error");
    exit;
  }
  return $binaryImg;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!isset($_FILES['editLanguageImage']) ||
    $_FILES['editLanguageImage']['error'] !== UPLOAD_ERR_OK
  ) {
    http_response_code(400);
    $errorMessage = "Image not attached or an upload error occurred.";
    $error = urlencode($errorMessage);
    header("Location: /php-pages/client-side/display_error.php?error=$error");
    exit;
  }

  $otherResourcesEdit = $_POST['otherResourcesEdit'];
  $imageName = $_FILES['editLanguageImage']['name'];
  $tmpPath   = $_FILES['editLanguageImage']['tmp_name'];
  $binaryImg = getImage($tmpPath, $imageName);

  $_SESSION['editLanguageImage']    = $binaryImg;
  $_SESSION['editImageName']        = $imageName;
  $_SESSION['otherResourcesEdit']   = $otherResourcesEdit;
  $_SESSION['editLanguageName']     = $_POST['editLanguageName']     ?? null;
  $_SESSION['editLanguageLogo']     = $_POST['editLanguageLogo']     ?? null;
  $_SESSION['editDefinition']       = $_POST['editDefinition']       ?? null;
  $_SESSION['editDescription']      = $_POST['editDescription']      ?? null;
  $_SESSION['edit_full_article']    = $_POST['edit_full_article']    ?? null;
  $_SESSION['edit_list_points']     = $_POST['edit_list_points']     ?? null;
  $_SESSION['editEasyToLearn']      = $_POST['editEasyToLearn']      ?? null;
  $_SESSION['editWebDev']           = $_POST['editWebDev']           ?? null;
  $_SESSION['editMobileDev']        = $_POST['editMobileDev']        ?? null;
  $_SESSION['editGameDev']          = $_POST['editGameDev']          ?? null;
  $_SESSION['editAiMl']             = $_POST['editAiMl']             ?? null;
  $_SESSION['editPerformance']      = $_POST['editPerformance']      ?? null;
  $_SESSION['editObjectOriented']   = $_POST['editObjectOriented']   ?? null;
  $_SESSION['editCommunitySupport'] = $_POST['editCommunitySupport'] ?? null;
  $_SESSION['editMarketDemand']     = $_POST['editMarketDemand']     ?? null;
  $_SESSION['editSyntaxSimplicity'] = $_POST['editSyntaxSimplicity'] ?? null;
  $_SESSION['editBackendDev']       = $_POST['editBackendDev']       ?? null;
  $_SESSION['editFrontendDev']      = $_POST['editFrontendDev']      ?? null;
  $_SESSION['edit-documentation']   = $_POST['editDocLink']          ?? null;
  $_SESSION['editVideoEmbed']       = $_POST['editVideoEmbed']       ?? null;
  $_SESSION['editCompilerUrl']      = $_POST['editCompilerUrl']      ?? null;
} else {
  http_response_code(405);
  $errorMessage = "Method Not Allowed. Undefined array key 'languageImage' at EditLanguages.php line 44";
  $error = urlencode($errorMessage);
  header("Location: /php-pages/client-side/display_error.php?error=$error");
  exit;
}

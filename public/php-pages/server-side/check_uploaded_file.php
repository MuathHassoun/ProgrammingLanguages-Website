<?php
$_alternative = $_FILES['image-file-to-check'];
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'tiff', 'svg', 'avif'];
$allowedMimeTypes = [
  'image/jpeg',
  'image/png',
  'image/gif',
  'image/webp',
  'image/bmp',
  'image/tiff',
  'image/svg+xml',
  'image/avif'
];

if (isset($_alternative) && $_alternative['error'] === UPLOAD_ERR_OK) {
  $languageImage = $_alternative;
  $tmpName = $languageImage['tmp_name'];
  $originalName = $languageImage['name'];

  $check = getimagesize($tmpName);
  $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
  $mimeType = mime_content_type($tmpName);

  if ($check !== false && in_array($extension, $allowedExtensions) && in_array($mimeType, $allowedMimeTypes)) {
    $uniqueName = uniqid('lang_', true) . '.' . $extension;
    $uploadDir = 'uploads/';
    $destination = $uploadDir . $uniqueName;

    if (!move_uploaded_file($tmpName, $destination)) {
      $_SESSION['error_message'] = "Failed to save the uploaded image.";
      $_SESSION['image_valid'] = false;
      header("Location: display_error.php");
      exit;
    }
    $_SESSION['image_valid'] = true;
  } else {
    $_SESSION['error_message'] = "The uploaded file is not a valid image. Allowed formats: JPG, PNG, GIF, WEBP, BMP, TIFF, SVG, AVIF.";
    $_SESSION['image_valid'] = false;
    header("Location: display_error.php");
    exit;
  }
} else {
  $_SESSION['error_message'] = "No image was uploaded or an error occurred during upload.";
  $_SESSION['image_valid'] = false;
  header("Location: display_error.php");
  exit;
}

/*
 * rename($languageImage['tmp_name'], $destination);
 * copy($languageImage['tmp_name'], $destination);
 * unlink($languageImage['tmp_name']);
 */

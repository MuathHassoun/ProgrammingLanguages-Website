<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$errorMsg = '';
if (!empty($_GET['error'])) {
  $errorMsg = $_GET['error'];
} elseif (!empty($_SESSION['error_message'])) {
  $errorMsg = $_SESSION['error_message'];
  unset($_SESSION['error_message']);
}

function currentDateTime(): string {
  return date('Y-m-d H:i:s');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="../../css/display-error-style.css">
  <link rel="icon" href="../../img/icon/icon.png" type="image/x-icon" />
  <title>Error Page</title>
</head>
<body>
  <div id="errorIcon" style="display:none; text-align:center; margin-top:3rem;">
    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#6c757d" class="bi bi-exclamation-triangle" viewBox="0 0 16 16" aria-hidden="true">
      <path d="M7.938 2.016a.13.13 0 0 1 .125 0l6.857 11.856c.04.07.04.152 0 .222a.13.13 0 0 1-.125.063H1.205a.13.13 0 0 1-.125-.063.202.202 0 0 1 0-.222L7.938 2.016zm.812.684L2.067 14h11.866L8.75 2.7z"/>
      <path d="M7.002 11a1 1 0 1 0 2 0 1 1 0 0 0-2 0zm.93-6.481a.5.5 0 0 0-.858 0L5.022 8.25a.5.5 0 0 0 .429.75h5.098a.5.5 0 0 0 .429-.75L7.93 4.519z"/>
    </svg>
    <p style="margin-top: 1rem; font-size: 1.1rem; color: #495057;">
      Please wait while we attempt to resolve the issue.<br />
      You may also return to the previous page to continue where you left off.
    </p>
  </div>

  <div class="error-container" role="alert" aria-live="assertive" aria-atomic="true">
    <button class="close-btn" aria-label="Close error message" onclick="showErrorIconAndHideError()">&times;</button>
    <h1>⚠️ An error occurred</h1>
    <div class="error-msg"><?= htmlspecialchars($errorMsg ?: 'Sorry, something went wrong but no detailed error message was provided. Please try again later or contact support if the issue persists.') ?></div>
    <div class="error-info">Time: <?= currentDateTime() ?></div>
    <button onclick="location.reload()">Reload page</button>
    <button onclick="history.back()" aria-label="Return to the previous page">Go Back</button>
  </div>

  <script>
    function showErrorIconAndHideError() {
      document.querySelector('.error-container').style.display = 'none';
      document.getElementById('errorIcon').style.display = 'block';
    }
  </script>
</body>
</html>

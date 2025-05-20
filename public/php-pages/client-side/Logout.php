<?php
  session_start();
  session_unset();
  session_destroy();

  session_start();
  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    echo <<<HTML
            <script src="../../js/actions/authentication-js.js"></script>
            <script>
              document.addEventListener("DOMContentLoaded", function () {
                returnAuth();
                returnLogout();
              });
            </script>
          HTML;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logout Page</title>
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/embellishment-style.css" />
  <link rel="stylesheet" href="../../css/waves-style.css" />
  <link rel="stylesheet" href="../../css/logout-style.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="icon" href="../../img/icon/icon.png" type="image/x-icon" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <header>
    <div class="logo">
      <a href="../../index.php" class="logo-link">
        <img class="logo-icon" src="../../img/icon/icon.png" alt="CodeWorld Logo" />
        <h1 class="website-title">CodeWorld!</h1>
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
      </div>
      <div class="nav-auth">
        <a href="Authentication.php#authentication-section" class="not-active-user">Authentication</a>
      </div>
    </nav>
  </header>

  <main class="logout-container">
    <div class="logout-box">
      <a href="Authentication.php#authentication-section" rel="noopener noreferrer"><i class='bx bx-log-out-circle logout-icon'></i></a>
      <h2>You have been logged out</h2>
      <p>Thank you for visiting. We hope to see you again soon.</p>
      <a href="../../index.php" class="btn-logout-home">Return to Homepage</a>
    </div>
  </main>

  <footer>
    <p>© 2025 Learn Programming Languages - All Rights Reserved</p>
  </footer>
  <div class="waves"></div>
</body>
</html>

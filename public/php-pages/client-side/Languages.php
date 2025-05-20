<?php
  session_start();
  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
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
  <link rel="stylesheet" href="../../css/languages-style.css" />
  <link rel="stylesheet" href="../../css/toggle-sidebar.css" />
  <link rel="icon" href="../../img/icon/icon.png" type="image/x-icon" />
  <title>Languages Page</title>
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
        <a href="Documentation.php#doc-section">Documentation</a>
        <a href="EditLanguages.php#edit-section">Edit/New</a>
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

  <main class="languages" id="languages-section">
    <button id="toggleSidebar" class="toggle-sidebar-btn">☰</button>
    <div class="sidebar">
      <?php
      foreach ($_SESSION["languages"] as $languageData) {
        echo '<button onclick="showLanguage(\'' . $languageData['name'] . '\')">' . $languageData['name'] . '</button>';
      }
      ?>
    </div>

    <div class="language-content">
      <?php
      /**
       * @return void
       */
      function embeddedResources(): void {
        foreach ($_SESSION["languages"] as $languageData) {
          if($languageData['name'] == 'Python'){
            echo '<div id="' . $languageData['name'] . '" class="language-frame" style="display: block;">';
          } else {
            echo '<div id="' . $languageData['name'] . '" class="language-frame" style="display: none;">';
          }

          echo '<h2> Learn ' . $languageData['name'] . ' and Try </h2>';
          echo '<iframe width="100%" height="400" src="' . $languageData['video_embed'] . '" allowfullscreen></iframe>';
          echo '<iframe class="compiler" width="100%" height="400" src="' . $languageData['online_compiler'] . '" allowfullscreen></iframe>';
          echo '<div class="extra-section">';
          echo '<h3> Additional Resources </h3>';
          echo '<ul>';
          try {
            foreach ($languageData['additional_resources'] as $resource) {
              echo '<li><a href="' . $resource['link'] . '" target="_blank">' . $resource['title'] . '</a></li>';
            }
          } catch (Exception $e) {
            die($e->getMessage());
          }
          echo '</ul>';
          echo '</div>';
          echo '</div>';
        }
      }
      embeddedResources();
      ?>
    </div>
  </main>

  <footer>
    <p>© 2025 Learn Programming Languages - All Rights Reserved</p>
  </footer>
  <div class="waves"></div>
  <script src="../../js/actions/languages-js.js"></script>
  <script src="../../js/actions/toggle-sidebar-js.js"></script>
</body>
</html>

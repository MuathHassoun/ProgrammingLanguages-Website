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
  <link rel="stylesheet" href="../../css/contact-style.css" />
  <link rel="stylesheet" href="../../css/compare-style.css" />
  <link rel="icon" href="../../img/icon/icon.png" type="image/x-icon" />
  <title>Compare Page</title>
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
        <a href="EditLanguages.php#edit-section">Edit/New</a>
        <a href="AboutUs.php#about-us-section">About Us</a>
        <a href="ContactUs.php#contact-us-section">Contact Us</a>
      </div>
      <div class="nav-auth">
        <a href="Authentication.php#authentication-section" class="not-active-user">Authentication</a>
        <a href="Logout.php" class="logout-not-active">Logout</a>
      </div>
    </nav>
  </header>

  <!-- Compare between the languages -->
  <main class="compare-page" id="compare-section">
    <section class="compare-section-header">
      <h1>Compare Programming Languages</h1>
      <p>
        Here are some of the most popular programming languages and their features.
        Choose the language that best suits your needs and start learning today!
      </p>
    </section>
    <section class="compare-table-container">
      <table>
        <caption>🧩 Comparison Table of the Languages</caption>
        <thead>
        <tr>
          <th>Language / Feature</th>
          <th>Easy to Learn</th>
          <th>Web Development</th>
          <th>Mobile Development</th>
          <th>Game Development</th>
          <th>Used in AI / ML</th>
          <th>Performance</th>
          <th>Object-Oriented</th>
          <th>Community Support</th>
          <th>Job Market Demand</th>
          <th>Syntax Simplicity</th>
          <th>Backend Development</th>
          <th>Frontend Development</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($_SESSION["languages"] as $languageData) {
          echo '<tr>';
          echo '<td class="language-cell">' . htmlspecialchars($languageData['name']) . '</td>';
          foreach ($languageData['features'] as $feature => $icon) {
            echo '<td>' . $icon . '</td>';
          }
          echo '</tr>';
        }
        ?>
        </tbody>
      </table>

      <p><strong>✅ = Excellent | 🟩 = Good | 🔶 = Moderate/Partially Supported | ❌ = Not Suitable</strong></p>
    </section>
    <section class="compare-second-part-section">
      <h2>Language Overviews</h2>
      <?php
      /**
       * @return void
       */
      function displayDefaultData(): void
      {
        foreach ($_SESSION["languages"] as $languageData) {
          echo '<article id="' . $languageData['name'] . '">';
          echo '<h3>' . $languageData["icon"] . ' ' . htmlspecialchars($languageData['name']) . '</h3>';

          $paragraphs = explode("\n", trim($languageData["full_article"]));
          foreach ($paragraphs as $para) {
            if (trim($para) !== '') {
              echo '<p>' . htmlspecialchars(trim($para)) . '</p>';
            }
          }

          if (!empty($languageData["list_points"])) {
            echo '<ol>';
            foreach ($languageData["list_points"] as $point) {
              if (str_contains($point, ':')) {
                list($title, $desc) = explode(':', $point, 2);
                echo '<li><strong>' . htmlspecialchars(trim($title)) . ':</strong> ' . htmlspecialchars(trim($desc)) . '</li>';
              } else {
                echo '<li>' . htmlspecialchars($point) . '</li>';
              }
            }
            echo '</ol>';
          }
          echo '</article>';
        }
      }
      displayDefaultData();
      ?>
    </section>
  </main>

  <footer>
    <p>© 2025 Learn Programming Languages - All Rights Reserved</p>
  </footer>
  <div class="waves"></div>
</body>
</html>

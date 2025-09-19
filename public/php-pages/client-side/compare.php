<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

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
  <link rel="stylesheet" href="../../css/popup-style.css" />
  <link rel="stylesheet" href="../../css/embellishment-style.css" />
  <link rel="stylesheet" href="../../css/waves-style.css" />
  <link rel="stylesheet" href="../../css/contact-style.css" />
  <link rel="stylesheet" href="../../css/compare-style.css" />
  <link rel="icon" href="../../img/icon/icon.png" type="image/x-icon" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        <a href="languages.php#languages-section">Learn Languages</a>
        <a href="documentation.php#doc-section">Documentation</a>
        <a href="edit_languages.php#edit-section">Edit/New</a>
        <a href="about_us.php#about-us-section">About Us</a>
        <a href="contact_us.php#contact-us-section">Contact Us</a>
      </div>
      <div class="nav-auth">
        <?php
        if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true) {
          echo '<a href="../admin-side/admin-dashboard.php#admin-dashboard-main" class="admin-dashboard">Dashboard </a>';
        }
        ?>
        <a href="authentication.php#authentication-section" class="not-active-user">Authentication</a>
        <a href="logout.php" class="logout-not-active">Logout</a>
        <?php
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
          include '../server-side/notifications.php';
          if (isset($_SESSION['has-notifications']) && $_SESSION['has-notifications'] === true) {
            $username = $_SESSION['username'] ?? '';
            $dataUsername = htmlspecialchars($username);
            echo '<div class="notification-container">
                    <a href="#" id="notification-btn" data-username="' . $dataUsername . '" class="notification-link notification-btn" title="Notifications">
                      <i class="fas fa-bell"></i>';

            if (isset($_SESSION['new_message_count']) && $_SESSION['new_message_count'] > 0) {
              echo '<span class="badge">' . $_SESSION['new_message_count'] . '</span>';
            }

            echo '  </a>
                    <div id="notification-popup" class="notification-popup hidden" dir="ltr">
                      <section class="message-section section-style">
                        <h2 class="popup-title">📨 New Replies</h2>
                        <hr>
                        <div class="message-list">';

            if (!empty($_SESSION['user-replies']) && is_array($_SESSION['user-replies'])) {
              foreach ($_SESSION['user-replies'] as $reply) {
                $from = htmlspecialchars($reply['from_username']);
                $subject = htmlspecialchars($reply['subject']);
                $date = htmlspecialchars($reply['reply_date']);
                $content = nl2br(htmlspecialchars($reply['reply_content']));

                echo <<<HTML
                        <div class="message-item">
                          <div class="message-header">
                            <p><strong>From:</strong> $from</p>
                            <p><strong>Subject:</strong> $subject</p>
                          </div>
                          <div class="message-body">
                            <p>$content</p>
                          </div>
                          <div class="message-footer">
                            <small><strong>Date:</strong> $date</small>
                          </div>
                        </div>
                        <hr>
                  HTML;
              }
            } else {
              echo '<div class="message-item"><p>No new replies.</p></div>';
            }

            echo '</div>
                  </section>
                </div>
              </div>';
          }
        }
        ?>
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
  <script src="../../js/actions/popup-js.js"></script>
</body>
</html>

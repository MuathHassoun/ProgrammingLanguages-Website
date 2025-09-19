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
  <title>About Us Page</title>
  <link rel="stylesheet" href="../../css/style.css" />
  <link rel="stylesheet" href="../../css/popup-style.css" />
  <link rel="stylesheet" href="../../css/embellishment-style.css" />
  <link rel="stylesheet" href="../../css/waves-style.css" />
  <link rel="stylesheet" href="../../css/about-style.css" />
  <link rel="icon" href="../../img/icon/icon.png" type="image/x-icon" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        <a href="compare.php#compare-section">Compare</a>
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

  <main class="about-us" id="about-us-section">
    <h2>About Us</h2>
    <p>
      Learn Programming Languages is a platform created by passionate Computer Engineers, dedicated to helping others learn programming in a simple and engaging way.
    </p>
    <div class="team-cards">
      <?php foreach ($_SESSION['admins-data'] as $username => $admin): ?>
        <div class="about-card-container">
          <div class="about-card-inner">
            <div class="about-card-front">
              <img src="<?php echo $admin['admin-photo'] ?? '/../../img/Our/admin-photo.jpg'; ?>" alt="<?php echo htmlspecialchars($admin['returned-full-name']); ?>">
              <h3><?php echo htmlspecialchars($admin['returned-full-name']); ?></h3>
              <p><?php echo htmlspecialchars($admin['short_self_introduction']); ?></p>
            </div>
            <div class="about-card-back">
              <h3>About Me</h3>
              <pre><?php echo nl2br(htmlspecialchars($admin['long_self_introduction'])); ?></pre>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <footer>
    <p>© 2025 Learn Programming Languages - All Rights Reserved</p>
  </footer>
  <div class="waves"></div>
  <script src="../../js/actions/popup-js.js"></script>
</body>
</html>

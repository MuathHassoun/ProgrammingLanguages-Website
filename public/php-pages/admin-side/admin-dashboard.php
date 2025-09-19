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

if (isset($_SESSION['delete-flag']) && $_SESSION['delete-flag'] === true) {
  $title = $_SESSION['callback_message-title'];
  $text = $_SESSION['callback_message-text'];
  echo '<script src="../../js/actions/sweet-alert2-js.js"></script>';
  echo "<script>
            document.addEventListener('DOMContentLoaded', () => {
                showAlert('success', '$title', '$text', '#0f1b30');
            });
          </script>";

  unset($_SESSION['delete-flag']);
  unset($_SESSION['callback_message-title']);
  unset($_SESSION['callback_message-text']);
} elseif (isset($_SESSION['delete-flag']) && $_SESSION['delete-flag'] === false) {
  $title = $_SESSION['callback_message-title'];
  $text = $_SESSION['callback_message-text'];
  echo '<script src="../../js/actions/sweet-alert2-js.js"></script>';
  echo "<script>
            document.addEventListener('DOMContentLoaded', () => {
                showAlert('error', '$title', '$text', '#d33');
            });
          </script>";

  unset($_SESSION['delete-flag']);
  unset($_SESSION['callback_message-title']);
  unset($_SESSION['callback_message-text']);
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
  <link rel="stylesheet" href="../../css/admin-role-style.css">
  <link rel="icon" href="../../img/icon/icon.png" type="image/x-icon" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <title>Admin Dashboard</title>
</head>
<body>
  <header>
    <div class="logo">
      <a href="../../index.php" class="logo-link">
        <img class="logo-icon" src="../../img/icon/icon.png" alt="CodeWorld Logo" />
        <h1 class="website-title">CodeWorld!</h1>
      </a>
      <a href="#admin-dashboard-main" class="section-subtitle-link">
        <h1 class="section-subtitle">Admin Dashboard</h1>
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
        <a href="../client-side/languages.php#languages-section">Learn Languages</a>
        <a href="../client-side/documentation.php#doc-section">Documentation</a>
        <a href="../client-side/edit_languages.php#edit-section">Edit/New</a>
        <a href="../client-side/compare.php#compare-section">Compare</a>
        <a href="../client-side/about_us.php#about-us-section">About Us</a>
        <a href="../client-side/contact_us.php#contact-us-section">Contact Us</a>
      </div>
      <div class="nav-auth">
        <a href="../client-side/authentication.php#authentication-section" class="not-active-user">Authentication</a>
        <a href="../client-side/logout.php" class="logout-not-active">Logout</a>
        <?php
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
          include '../server-side/notifications.php';
          if (isset($_SESSION['has-notifications']) && $_SESSION['has-notifications'] === true) {
            $username = $_SESSION['username'] ?? '';
            $dataUsername = htmlspecialchars($username);
            echo '<div class="admin-notification-container-popup">
            <a href="#" id="notification-btn" data-username="' . $dataUsername . '" class="notification-link notification-btn" title="Notifications">
              <i class="fas fa-bell"></i>';

            if (isset($_SESSION['new_message_count']) && $_SESSION['new_message_count'] > 0) {
              echo '<span class="badge">' . $_SESSION['new_message_count'] . '</span>';
            }

            echo '  </a>
            <div id="notification-popup" class="admin-notification-popup-popup hidden" dir="ltr">
              <section class="admin-message-section-popup section-style">
                <h2 class="popup-title">📨 New Contacts</h2>
                <hr>
                <div class="admin-message-list-popup">';

            if (!empty($_SESSION['user-contacts']) && is_array($_SESSION['user-contacts'])) {
              foreach ($_SESSION['user-contacts'] as $contact) {
                $from = htmlspecialchars($contact['name']);
                $email = htmlspecialchars($contact['email']);
                $subject = htmlspecialchars($contact['subject']);
                $date = htmlspecialchars($contact['created_at']);
                $content = nl2br(htmlspecialchars($contact['message']));

                echo <<<HTML
                        <div class="admin-message-item-popup">
                          <div class="message-header">
                            <p><strong>From:</strong> $from ($email)</p>
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

  <main class="admin-dashboard-main" id="admin-dashboard-main">
    <section class="admin-profile section-style">
      <?php
      $conn = require(__DIR__ . '/../database-dir/connect.php');
      if (!$conn || $_SESSION['db_connected'] === false) {
        error_log("Database connection failed: " . $conn->connect_error);
        $_SESSION['error_message'] = "We encountered a technical issue while processing your request. Please try again later.";
        header("Location: /php-pages/client-side/display_error.php");
        exit;
      }

      $admin_username = $_SESSION['username'] ?? null;
      if (!$admin_username) {
        echo "<p>Error: Admin not logged in.</p>";
      } else {
        $stmt = $conn->prepare("SELECT username, full_name, admin_photo, email, short_self_introduction, long_self_introduction FROM admins WHERE username = ?");
        $stmt->bind_param("s", $admin_username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()):
          ?>
          <div class="header-container btn-container">
            <div class="profile-image header-of-info">
              <img src="data:image/jpeg;base64,<?= base64_encode($row['admin_photo']) ?>" alt="Admin Photo">
            </div>
            <div class="header-title">
              <h2><?= htmlspecialchars($row['full_name']) ?></h2>
              <small><?= htmlspecialchars($row['email']) ?></small>
            </div>
            <div class="profile-btn">
              <button id="edit-profile-btn">
                <i class="fas fa-user-edit"></i>
              </button>
              <button id="toggle-info-btn">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>
          <div class="profile-container" id="extra-info" style="display: none">
            <div class="profile-image">
              <img id="profile-photo" src="data:image/jpeg;base64,<?= base64_encode($row['admin_photo']) ?>" alt="Admin Photo">
            </div>
            <div class="profile-info">
              <h2><?= htmlspecialchars($row['full_name']) ?></h2>
              <p><strong>Username:</strong> <?= htmlspecialchars($row['username']) ?></p>
              <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
              <p><strong>Short Introduction:</strong><br><?= nl2br(htmlspecialchars($row['short_self_introduction'])) ?></p>
              <div class="long-intro">
                <h3>About Me</h3>
                <p><?= nl2br(htmlspecialchars($row['long_self_introduction'])) ?></p>
              </div>
            </div>
          </div>
        <?php
        else:
          echo "<p>No admin profile found.</p>";
        endif;

        $stmt->close();
      }
      ?>
    </section>

    <section class="add-user-section section-style">
      <h2>➕ Add Admin</h2>
      <form class="add-user-form" action="add_admin_handler.php" method="POST">
        <div class="form-group">
          <input type="text" name="admin-full_name" placeholder="Full Name" required>
          <input type="email" name="admin-email" placeholder="Email Address" required>
          <input type="text" name="admin-username" placeholder="Username" required>
          <input type="password" name="admin-password" placeholder="Password" required>
        </div>
        <div class="form-actions">
          <button type="submit">Add Admin</button>
        </div>
      </form>
    </section>

    <section class="add-user-section section-style">
      <h2>➕ Add Client</h2>
      <form class="add-user-form" action="add_client_handler.php" method="POST">
        <div class="form-group">
          <input type="text" name="full_name" placeholder="Full Name" required>
          <input type="email" name="email" placeholder="Email Address" required>
          <input type="text" name="add-username" placeholder="Username" required>
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="form-actions">
          <button type="submit">Add Client</button>
        </div>
      </form>
    </section>

    <div class="tabs-container section-style">
      <div class="tabs">
        <button class="tab-button active" data-tab="messages">📥 Messages</button>
        <button class="tab-button" data-tab="admins">👥 Admins</button>
        <button class="tab-button" data-tab="clients">👥 Clients</button>
      </div>

      <div id="messages" class="tab-content active">
        <section class="message-section-dash section-style">
          <h2>📥 Contact Messages</h2>
          <div class="message-list-dash">
            <?php
            $sql = "SELECT * FROM contact_messages";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $id = htmlspecialchars($row['id']);
                $name = htmlspecialchars($row['name']);
                $email = htmlspecialchars($row['email']);
                $subject = htmlspecialchars($row['subject']);
                $message = nl2br(htmlspecialchars($row['message']));
                $created_at = htmlspecialchars($row['created_at']);
                $username = htmlspecialchars($row['username']);

                echo <<<HTML
                        <div class="message-item-dash">
                          <div class="message-header-dash">
                            <button class="delete-message-btn" id="delete-message-btn" data-id="$id" title="Delete Message">&times;</button>
                            <button class="reply-message-btn" id="reply-message-btn" data-id="$id" data-name="$name" data-username="$username" data-subject="$subject" title="Reply to Message">📨</button>
                            <p><strong>From:</strong> $name ($email)</p>
                            <p><strong>Subject:</strong> $subject</p>
                          </div>
                          <div class="message-body-dash">
                            <p>$message</p>
                          </div>
                          <div class="message-footer-dash">
                            <small><strong>Username:</strong> $username </small>
                            <small><strong>Date:</strong> $created_at</small>
                          </div>
                        </div>
                      HTML;
              }
              $result->free();
            }
            ?>
          </div>
        </section>
      </div>

      <div id="admins" class="tab-content">
        <section class="user-table-section section-style">
          <h2>👥 All Admins</h2>
          <div class="user-table">
            <table>
              <thead>
              <tr class="table-header">
                <th>#</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
              </tr>
              </thead>
              <tbody>
              <?php
              $sql = "SELECT * FROM admins";
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                $i = 1;
                while ($row = $result->fetch_assoc()) {
                  $username = htmlspecialchars($row['username']);
                  $full_name = htmlspecialchars($row['full_name']);
                  $email = htmlspecialchars($row['email']);
                  $active = '';
                  if($username === $_SESSION['username']){
                    $active = $username;
                    continue;
                  } elseif ($username === "admin-muath29") {
                    continue;
                  }

                  echo <<<USERTABLE
                        <tr>
                          <td>$i</td>
                          <td>$full_name</td>
                          <td>$username</td>
                          <td>$email</td>
                          <td><button data-active="$active" data-username="$username" class="delete-admin-btn">Delete</button></td>
                        </tr>
                        USERTABLE;
                  $i++;
                }
                $result->free();
              }
              ?>
              </tbody>
            </table>
          </div>
        </section>
      </div>

      <div id="clients" class="tab-content">
        <section class="user-table-section section-style">
          <h2>👥 All Clients</h2>
          <div class="user-table">
            <table>
              <thead>
                <tr class="table-header">
                  <th>#</th>
                  <th>Full Name</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $sql = "SELECT * FROM users";
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                $i = 1;
                while ($row = $result->fetch_assoc()) {
                  $username = htmlspecialchars($row['username']);
                  $full_name = htmlspecialchars($row['full_name']);
                  $email = htmlspecialchars($row['email']);

                  echo <<<USERTABLE
                        <tr>
                          <td>$i</td>
                          <td>$full_name</td>
                          <td>$username</td>
                          <td>$email</td>
                          <td><button data-username="$username" class="delete-btn">Delete</button></td>
                        </tr>
                        USERTABLE;
                  $i++;
                }
                $result->free();
              }
              $conn->close();
              ?>
              </tbody>
            </table>
          </div>
        </section>
      </div>
    </div>
  </main>

  <footer>
    <p>© 2025 Learn Programming Languages - All Rights Reserved</p>
  </footer>
  <div class="waves"></div>
  <script src="../../js/actions/sweet-alert2-js.js"></script>
  <script src="../../js/actions/admin-dashboard-js.js"></script>
  <script src="../../js/actions/admin-profile-js.js"></script>
  <script src="../../js/actions/popup-js.js"></script>
</body>
</html>

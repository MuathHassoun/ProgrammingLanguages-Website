<?php
  session_start();
  if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['login_msg'] = 'Log in to get in touch with us — we’re here to help!';
    $_SESSION['send_from'] = 'contact-us-page';
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

    echo '<script src="../../js/actions/sweet-alert2-js.js"></script>';
    echo "<script>
          document.addEventListener('DOMContentLoaded', () => {
              showAlert('success', '<?= $title ?>', '<?= $text ?>', '#0f1b30');
          });
        </script>";
  }

  if (isset($_POST['subject'], $_POST['name'], $_POST['email'], $_POST['message']) && isset($_SESSION['username'])) {
    $subject = trim($_POST['subject']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    $from = trim($_SESSION['username']);

    $conn = new mysqli("sql209.infinityfree.com", "if0_39035367", "1pKEWmDL12VrMX", "if0_39035367_XXX");
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    if ($subject && $name && $email && $message && $from) {
      $stmt = $conn->prepare("INSERT INTO contact_messages (username, subject, name, email, message) VALUES (?, ?, ?, ?, ?)");
      if ($stmt) {
        $stmt->bind_param("sssss", $from, $subject, $name, $email, $message);
        $username = htmlspecialchars($from);

        echo '<script src="../../js/actions/sweet-alert2-js.js"></script>';
        if ($stmt->execute()) {
          echo "<script>
                  window.onload = function() {
                      showAlert('success', 'Message Sent!', 'Thank you, " . htmlspecialchars($name) . ". Your contact request has been received. We\'ll get back to you soon.', '#0f1b30');
                  };
                </script>";
        } else {
          echo "<script>
                  window.onload = function() {
                      showAlert('error', 'Submission Failed', 'An error occurred while sending your message. Please try again later.', '#330000');
                  };
                </script>";
        }
        $stmt->close();
        $conn->close();
      } else {
        echo "<script>
                window.onload = function() {
                    showAlert('error', 'Server Error', 'Unable to process your request at the moment. Please try again later.', '#330000');
                };
              </script>";
      }
    } else {
      echo "<script>
              window.onload = function() {
                  showAlert('warning', 'Missing Fields', 'Please fill in all required fields in the contact form.', '#664400');
              };
            </script>";
    }
  } else {
    echo "<script>
            window.onload = function() {
                showAlert('warning', 'Session Error', 'Please log in before using the contact form.', '#664400');
            };
          </script>";
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us Page</title>
  <link rel="stylesheet" href="../../css/style.css" />
  <link rel="stylesheet" href="../../css/embellishment-style.css" />
  <link rel="stylesheet" href="../../css/waves-style.css" />
  <link rel="stylesheet" href="../../css/contact-style.css" />
  <link rel="icon" href="../../img/icon/icon.png" type="image/x-icon" />
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
        <a href="Compare.php#compare-section">Compare</a>
        <a href="AboutUs.php#about-us-section">About Us</a>
      </div>
      <div class="nav-auth">
        <a href="Authentication.php#authentication-section" class="not-active-user">Authentication</a>
        <a href="Logout.php" class="logout-not-active">Logout</a>
      </div>
    </nav>
  </header>

  <!-- Contact Us Section -->
  <main class="contact-us" id="contact-us-section">
    <div class="contact-us-image">
    </div>
    <div class="contact-us-content">
      <h2>Contact Us</h2>
      <p>
        If you have any questions, please feel free to contact us.
      </p>
      <form action="" method="post">
        <section class="contact-form">
          <label for="subject">Subject</label>
          <input type="text" id="subject" name="subject" placeholder="Enter message subject" required/>
        </section>
        <section class="contact-form">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" placeholder="Enter your name" required/>
        </section>
        <section class="contact-form">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" required/>
        </section>
        <section class="contact-form">
          <label for="message">Message</label>
          <textarea name="message" id="message" cols="30" rows="10" placeholder="Enter your message" required></textarea>
        </section>
        <section class="contact-form">
          <button type="submit">Send</button>
        </section>
      </form>
    </div>
  </main>

  <!-- Footer -->
  <footer>
    <p>© 2025 Learn Programming Languages - All Rights Reserved</p>
  </footer>

  <!-- Water Wave -->
  <div class="waves"></div>
</body>
</html>

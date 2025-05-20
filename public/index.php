<?php
  session_start();
  if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    include './php-pages/server-side/PrepareDataFromDatabase.php';
  } else {
    $_SESSION['logged_in'] = false;
    $_SESSION['new-user'] = false;
    include './php-pages/server-side/PrepareInitialData.php';
  }

  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    echo <<<HTML
          <script src="js/actions/authentication-js.js"></script>
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
    $title = htmlspecialchars($msgParts[0] ?? 'Success', ENT_QUOTES);
    $text  = htmlspecialchars($msgParts[1] ?? '', ENT_QUOTES);
    unset($_SESSION['successful_msg']);

    echo '<script src="js/actions/sweet-alert2-js.js"></script>';
    echo "<script>
            document.addEventListener('DOMContentLoaded', () => {
                showAlert('success', '{$title}', '{$text}', '#0f1b30');
            });
          </script>";
  } elseif (isset($_SESSION['msg-from-edit-page'])) {
    $status = htmlspecialchars($_SESSION['status'], ENT_QUOTES);
    $msg_title = htmlspecialchars($_SESSION['msg_title'], ENT_QUOTES);
    $message = htmlspecialchars($_SESSION['message'], ENT_QUOTES);

    echo '<script src="js/actions/sweet-alert2-js.js"></script>';
    echo "<script>
              document.addEventListener('DOMContentLoaded', () => {
                  showAlert('{$status}', '{$msg_title}', '{$message}', '#0f1b30');
              });
            </script>";
    unset($_SESSION['msg-from-edit-page']);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CodeWorld Website!</title>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/embellishment-style.css" />
  <link rel="stylesheet" href="css/waves-style.css" />
  <link rel="icon" href="img/icon/icon.png" type="image/x-icon" />
</head>
<body>
  <!-- Header -->
  <header>
    <div class="logo">
      <a href="index.php" class="logo-link">
        <img class="logo-icon" src="img/icon/icon.png" alt="CodeWorld Logo" />
        <h1 class="website-title">CodeWorld!</h1>
      </a>
      <a href="#learn-section" class="section-subtitle-link">
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
        <a href="index.php">Home</a>
        <a href="php-pages/client-side/Languages.php#languages-section">Learn Languages</a>
        <a href="php-pages/client-side/Documentation.php#doc-section">Documentation</a>
        <a href="php-pages/client-side/EditLanguages.php#edit-section">Edit/New</a>
        <a href="php-pages/client-side/Compare.php#compare-section">Compare</a>
        <a href="php-pages/client-side/AboutUs.php#about-us-section">About Us</a>
        <a href="php-pages/client-side/ContactUs.php#contact-us-section">Contact Us</a>
      </div>
      <div class="nav-auth">
        <a href="php-pages/client-side/Authentication.php#authentication-section" class="not-active-user">Authentication</a>
        <a href="php-pages/client-side/Logout.php" class="logout-not-active">Logout</a>
      </div>
    </nav>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <h1>Welcome to CodeWorld!</h1>
    <h2>Start Your Coding Journey Today</h2>
    <p>Explore different programming languages and learn how to build websites and apps from scratch.</p>
    <a href="php-pages/client-side/Languages.php#languages-section">Get Started</a>
  </section>

  <!-- Languages Section -->
  <section class="languages">
    <h2>Popular Programming Languages</h2>
    <div class="language-cards">
      <?php
      foreach ($_SESSION["languages"] as $key => $language) {
        echo <<<cards
              <div class="card-container">
                <section class="card-wrapper">
                  <div class="inner-card">
                    <div class="card-front">
                      <div class="card-front-image">
                        <img src="{$language['image']}" alt="{$language['name']}">
                      </div>
                      <h3>{$language['name']}</h3>
                      <p>{$language['definition']}</p>
                      <a href="php-pages/client-side/Compare.php#{$language['name']}">Learn More</a>
                    </div>
                    <div class="card-back">
                      <h3>{$language['name']}</h3>
                      <pre>{$language['description']}</pre>
                    </div>
                    <div class="toggle-arrow">&#9654;</div>
                  </div>
                </section>
              </div>
        cards;
      }
      ?>
    </div>
  </section>

  <section id="learn-section" class="learn-section">
    <h2 class="section-title">How to Learn Programming Languages</h2>
    <p>
      Learning how to program opens up a world of possibilities — from building websites and apps, to automating tasks, analyzing data, and even creating games and AI systems.
      But where do you begin? This guide walks you through a clear, structured path to becoming a confident programmer.
    </p>

    <h3 class="learn-section-subtitle">1. Start With the Right Language</h3>
    <p>
      If you're new, don’t overthink it. Choose a beginner-friendly language like <strong>Python</strong> or <strong>JavaScript</strong>. These languages are widely used, readable, and have massive communities full of tutorials and support.
    </p>

    <h3 class="learn-section-subtitle">2. Master the Fundamentals</h3>
    <p>
      Focus on core programming concepts first: <strong>variables, data types, loops, conditionals, functions,</strong> and <strong>arrays/lists</strong>. These are the building blocks of any language.
      Don’t rush — take your time to understand how things work and practice writing simple programs.
    </p>

    <h3 class="learn-section-subtitle">3. Build Real Projects</h3>
    <p>The best way to learn is by doing. Start small:</p>
    <ul>
      <li>Build a simple calculator</li>
      <li>Create a personal to-do list app</li>
      <li>Design a basic portfolio website</li>
      <li>Automate a repetitive task on your computer</li>
    </ul>
    <p>These projects will help you connect the theory to real-world problem-solving.</p>

    <h3 class="learn-section-subtitle">4. Use Quality Learning Resources</h3>
    <p>
      Make use of trusted platforms:
      <br>👉 <a href="https://www.freecodecamp.org/" target="_blank" class="external-link">FreeCodeCamp</a>
      <br>👉 <a href="https://www.w3schools.com/" target="_blank" class="external-link">W3Schools</a>
      <br>👉 <a href="https://www.codecademy.com/" target="_blank" class="external-link">Codecademy</a>
    </p>

    <h3 class="learn-section-subtitle">5. Join a Developer Community</h3>
    <p>
      Learning alone can be tough. Surround yourself with other learners and developers:
      <br>🌐 Ask questions on <strong>Stack Overflow</strong>
      <br>💬 Join Discord servers or Reddit communities
      <br>🤝 Contribute to open source on <strong>GitHub</strong>
    </p>

    <h3 class="learn-section-subtitle">6. Practice Through Challenges</h3>
    <p>
      Websites like <a href="https://leetcode.com/" target="_blank" class="external-link">LeetCode</a> and <a href="https://www.hackerrank.com/" target="_blank" class="external-link">HackerRank</a> offer algorithm challenges to boost your logical thinking and speed.
    </p>

    <h3 class="learn-section-subtitle">7. Stay Consistent & Curious</h3>
    <p>
      Programming is not about memorizing code — it’s about solving problems. Code a little every day, learn from your mistakes, and always stay curious. Explore how things work, read documentation, and never stop building.
    </p>

    <p class="final-tip">
      🚀 Tip: The key to success is not speed, but consistency. Make coding a habit, and progress will come.
    </p>
  </section>

  <!-- Footer -->
  <footer>
    <p>© 2025 Learn Programming Languages - All Rights Reserved</p>
  </footer>

  <!-- Water Wave -->
  <div class="waves"></div>
  <script src="js/app.js"></script>
</body>
</html>

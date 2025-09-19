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
  <link rel="stylesheet" href="../../css/forgot-password-style.css" />
  <link rel="icon" href="../../img/icon/icon.png" type="image/x-icon" />
  <title>Forgot Password! Page</title>
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
      </div>
    </nav>
  </header>

  <!-- Forgot Password Section -->
  <main class="forgot-password" id="forgot-password-section">
    <section class="forgot-container">
      <div class="forgot-img">
      </div>
      <div class="forgot-content">
        <h1>Forgot Your Password?</h1>
        <p>
          Please enter your email address below. We'll send you a link to reset your password.
        </p>
        <form action="">
          <section class="forgot-form">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter your email address" required/>
          </section>
          <button type="submit">Send Reset Link</button>
        </form>
        <section class="forgot-links">
          <p class="other-actions-label">Other Actions</p>
          <ul class="other-actions">
            <li><a href="authentication.php?mode=login#authentication-section">Back to Login Page</a></li>
            <li><a href="authentication.php?mode=register#authentication-section">Create an Account</a></li>
          </ul>
        </section>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer>
    <p>© 2025 Learn Programming Languages - All Rights Reserved</p>
  </footer>
  <!-- Water Wave -->
  <div class="waves"></div>
</body>
</html>

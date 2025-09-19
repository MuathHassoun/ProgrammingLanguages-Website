<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Page Not Found</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" href="./public/img/icon/icon.png" type="image/x-icon" />
  <style>
    :root {
      --color-header-footer: #12213a;
      --color-background: #0f172a;
      --color-primary-text: #e2e8f0;
      --color-secondary-text: #94a3b8;
      --color-final-tip: #64748b;
      --color-link: #38bdf8;
      --color-link-hover: #0ea5e9;
      --color-card-front: #2d3e5f;
      --color-card-back: #24344d;
      --color-card-shadow: rgba(0, 0, 0, 0.2);
      --color-toggle-arrow: #ffffff;
      --color-toggle-hover: #3b4d6b;
      --color-learn-bg: #12213a;
      --color-learn-subtitle: #60c3f9;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      line-height: 1.4;
    }

    html, body {
      height: 100%;
      width: 100%;
      background-color: var(--color-background);
      color: var(--color-primary-text);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 1rem;
    }

    body {
      flex-direction: column;
    }

    h1 {
      font-size: 3rem;
      font-weight: 700;
      color: var(--color-link);
      margin-bottom: 0.5rem;
      text-shadow: 0 0 5px var(--color-link);
    }

    p {
      max-width: 320px;
      font-size: 1.25rem;
      color: var(--color-secondary-text);
      margin: 0 auto;
    }

    @media (max-width: 400px) {
      h1 {
        font-size: 2rem;
      }

      p {
        font-size: 1rem;
        max-width: 90%;
      }
    }
  </style>
</head>

<body>
<h1>Page Not Found</h1>
<p>Sorry, but the page you were trying to view does not exist.</p>
</body>

</html>

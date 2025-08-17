<?php
session_start();
?>
<?php
require_once __DIR__ . './navbar/init.php';
$active = 'about';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us - Lunch Box</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    :root {
      --bg:#0f172a;
      --card:#0b1227;
      --muted:#94a3b8;
      --text:#e2e8f0;
      --primary:#22c55e;
      --accent:#38bdf8;
      --border:#1e293b;
    }
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: var(--bg);
      color: var(--text);
    }
    nav {
      display: flex;
      justify-content: center;
      gap: 20px;
      padding: 14px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
      border-bottom: 2px solid var(--border);
    }
    nav a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      padding: 8px 14px;
      border-radius: 6px;
      transition: background 0.2s;
    }
    nav a:hover {
      background: rgba(255,255,255,0.15);
    }
    header {
      text-align: center;
      padding: 30px 20px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
      color: white;
    }
    main {
      max-width: 1000px;
      margin: auto;
      padding: 30px;
      line-height: 1.6;
    }
    .team {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }
    .team-card {
      background: var(--card);
      padding: 20px;
      border-radius: 12px;
      text-align: center;
      border: 1px solid var(--border);
    }
    .team-card img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      margin-bottom: 15px;
      object-fit: cover;
    }
    .footer {
      text-align: center;
      padding: 20px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
      color: white;
      margin-top: 30px;
    }
  </style>
</head>
<body>
    <!-- <nav>
    <a href="index.php">Home</a>
    <a href="suggestions.php">Suggestions</a>

    <a href="aboutus.php">About Us</a>
    <a href="contact.php">Contact</a>


    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="login.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="signup.php">Sign Up</a>
    <?php endif; ?>
  </nav> -->
<?php include __DIR__ . './navbar/navbar.php'; ?>

  <header>
    <h1>About Lunch Box</h1>
    <p>Healthy & Affordable Meals for Everyone in Bangladesh</p>
  </header>

  <main>
    <h2>Our Mission</h2>
    <p>
      Lunch Box was founded with a simple vision: to make healthy and affordable meals accessible 
      to students and working individuals across Bangladesh. We provide daily nutritious meals tailored 
      to the needs of school children, university students, and adults.
    </p>

    <h2>Why Choose Us?</h2>
    <ul>
      <li>Fresh and hygienic ingredients every day.</li>
      <li>Balanced nutrition designed for different age groups.</li>
      <li>Affordable pricing and flexible subscription plans.</li>
      <li>Fast delivery service to schools, universities, and offices.</li>
    </ul>

    <h2>Meet Our Team</h2>
    <div class="team">
      <div class="team-card">
        <img src="https://avatars.githubusercontent.com/u/156079524?v=4" alt="Maruf Hasan">
        <h3>Md. Maruf Hasan</h3>
        <p>Founder & CEO</p>
      </div>
      <div class="team-card">
        <img src="https://i.pravatar.cc/100?img=2" alt="Team Member">
        <h3>Ayesha Akter</h3>
        <p>Nutrition Specialist</p>
      </div>
      <div class="team-card">
        <img src="https://i.pravatar.cc/100?img=3" alt="Team Member">
        <h3>Hasan Ali</h3>
        <p>Operations Manager</p>
      </div>
      <div class="team-card">
        <img src="https://i.pravatar.cc/100?img=4" alt="Team Member">
        <h3>Ruma Chowdhury</h3>
        <p>Customer Support</p>
      </div>
    </div>
  </main>

  <div class="footer" id="contact">
    <p>&copy; 2025 Lunch Box Bangladesh. All rights reserved.</p>
    <p>Contact: +8801784925341 | lunchboxbd@diu.com</p>
  </div>
</body>
</html>

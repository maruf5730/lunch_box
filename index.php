<?php
session_start();

// Handle form submissions for both subscription and comment
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['form_type'])) {
        $form_type = $_POST['form_type'];
        $host = "localhost";
        $db = "lunch_box";
        $user = "root";
        $pass = "";

        $conn = new mysqli($host, $user, $pass, $db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Subscription form logic
        if ($form_type === 'subscription' &&
            isset($_POST['package_name'], $_POST['user_name'], $_POST['email'], $_POST['phone'], $_POST['address'])
        ) {
            $package_name = $_POST['package_name'];
            $user_name = $_POST['user_name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            $stmt = $conn->prepare("INSERT INTO custom_package (package_name, user_name, email, phone, address) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $package_name, $user_name, $email, $phone, $address);

            if ($stmt->execute()) {
                $_SESSION['success'] = true;
            } else {
                $_SESSION['error'] = $stmt->error;
            }
        }

        // Comment form logic
        elseif ($form_type === 'comment' &&
            isset($_POST['name'], $_POST['email'], $_POST['comment'])
        ) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $comment = $_POST['comment'];

            $stmt = $conn->prepare("INSERT INTO comment (name, email, comment) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $comment);

            if ($stmt->execute()) {
                $_SESSION['comment_success'] = true;
            } else {
                $_SESSION['comment_error'] = $stmt->error;
            }
        }

        $stmt->close();
        $conn->close();

        // Redirect to avoid resubmission on refresh
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lunch Box Nutrition Chart</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <nav>
    <a href="index.php">Home</a>
    <a href="#about">About Us</a>
    <a href="#contact">Contact</a>
    <a href="#">Login</a>
    <a href="#">Sign Up</a>
  </nav>
  <header>
    <h1>Lunch Box – Nutritional Needs in Bangladesh</h1>
    <p>Healthy Daily Meals for Primary, High School, University Students, and Adult Men</p>
  </header>
  <img class="banner-img" src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1200&q=80" alt="Healthy Lunch Banner" />

  

  <main>
    <?php include 'nutrition.php'; ?>
    <?php include 'menu.php'; ?>
    <?php include 'form.php'; ?>      <!-- Subscription form -->
        <div style="display: flex; justify-content: center; align-items: center; margin: 40px 0;">
      <a href="package.php" class="custom-btn" style="
      background: linear-gradient(90deg, #43ea6e 0%, #2dbd4f 100%);
      color: #fff;
      padding: 18px 48px;
      font-size: 1.5rem;
      font-weight: bold;
      border: none;
      border-radius: 40px;
      box-shadow: 0 4px 16px rgba(67, 234, 110, 0.2);
      text-decoration: none;
      transition: background 0.3s, transform 0.2s;
      text-align: center;
      letter-spacing: 1px;
      " onmouseover="this.style.background='#2dbd4f'; this.style.transform='scale(1.05)';"
      onmouseout="this.style.background='linear-gradient(90deg, #43ea6e 0%, #2dbd4f 100%)'; this.style.transform='scale(1)';">
      Custom Order
      </a>
    </div>
    <?php include 'comment.php'; ?>   <!-- Comment modal -->
    <?php include 'testimonial.php'; ?>
  </main>

  <div class="footer">
    <p>&copy; 2025 Lunch Box Bangladesh. All rights reserved.</p>
    <p id="about">About Us: We provide healthy and affordable lunch to students and working men across Bangladesh with care and nutrition in mind.</p>
    <p id="contact">Emergency Contact: +8801784925341 | lunchboxbd@diu.com</p>
  </div>
</body>
</html>

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
        if (
            $form_type === 'subscription' &&
            isset($_POST['package_name'], $_POST['user_name'], $_POST['email'], $_POST['phone'], $_POST['address'])
        ) {
            $package_name = $_POST['package_name'];
            $user_name    = $_POST['user_name'];
            $email        = $_POST['email'];
            $phone        = $_POST['phone'];
            $address      = $_POST['address'];

            // Adjust to your real schema if needed
            $stmt = $conn->prepare(
                "INSERT INTO custom_package (package_name, user_name, email, payment_number, transaction_id)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $dummyPayment = $phone; // temporary mapping
            $dummyTxn     = 'N/A';  // change if you collect it
            $stmt->bind_param("sssss", $package_name, $user_name, $email, $dummyPayment, $dummyTxn);

            if ($stmt->execute()) {
                $_SESSION['success'] = true;
            } else {
                $_SESSION['error'] = $stmt->error;
            }
        }
        // Comment form logic
        elseif (
            $form_type === 'comment' &&
            isset($_POST['name'], $_POST['email'], $_POST['comment'])
        ) {
            $name    = $_POST['name'];
            $email   = $_POST['email'];
            $comment = $_POST['comment'];

            $stmt = $conn->prepare("INSERT INTO comment (name, email, comment) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $comment);

            if ($stmt->execute()) {
                $_SESSION['comment_success'] = true;
            } else {
                $_SESSION['comment_error'] = $stmt->error;
            }
        }

        if (isset($stmt) && $stmt) $stmt->close();
        $conn->close();

        header("Location: index.php");
        exit();
    }
}
?>
<?php
require_once __DIR__ . './navbar/init.php';
$active = 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lunch Box Nutrition Chart</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    :root {
      --bg:#0f172a; --card:#0b1227; --muted:#94a3b8; --text:#e2e8f0;
      --primary:#22c55e; --accent:#38bdf8; --border:#1e293b;
    }
    body {
      margin:0; font-family:Arial, sans-serif;
      background:
        radial-gradient(1200px 600px at 10% -20%, rgba(252, 252, 252, 0.12), transparent 60%),
        radial-gradient(1000px 500px at 100% 10%, rgba(56,189,248,0.10), transparent 60%),
        var(--bg); color:var(--text);
    }
    nav {
      display:flex; align-items:center; justify-content:center; gap:16px; padding:12px 16px;
      background:linear-gradient(90deg, var(--primary), var(--accent)); border-bottom:2px solid var(--border);
      position:sticky; top:0; z-index:50;
    }
    nav .left, nav .right { display:flex; align-items:center; gap:12px; }
    nav a {
      color:white; text-decoration:none; font-weight:bold; padding:8px 12px;
      border-radius:8px; transition:background .2s;
    }
    nav a:hover { background:rgba(255,255,255,.15); }
    nav .spacer { flex:1; }
    .user-badge {
      display:flex; align-items:center; gap:8px; padding:6px 10px; border-radius:999px;
      background:rgba(0,0,0,.18); color:#fff; font-weight:600;
      border:1px solid rgba(255,255,255,.2);
    }
    .user-icon {
      width:28px; height:28px; border-radius:50%;
      background:#ffffff22; display:grid; place-items:center; border:1px solid #ffffff44;
    }
    .user-icon svg { width:18px; height:18px; fill:#fff; opacity:.9; }
    header { text-align:center; padding:20px; }
    header p { color:var(--muted); }
    .banner-img { width:100%; height:350px; object-fit:cover; display:block; border-bottom:3px solid var(--border); }
    main { max-width:1100px; margin:auto; padding:20px; }
    .footer { text-align:center; padding:20px; background:linear-gradient(90deg, var(--primary), var(--accent)); color:white; margin-top:30px; }
    .custom-btn { display:inline-block; text-decoration:none; font-weight:bold; }
  </style>
</head>
<body>

<?php include __DIR__ . './navbar/navbar.php'; ?>

  <header>
    <h1>Lunch Box – Nutritional Needs in Bangladesh</h1>
    <p>Healthy Daily Meals for Primary, High School, University Students, and Adult Men</p>
  </header>

  <div style="position: relative; width: 100%; height: 350px; overflow: hidden;">
    <img class="banner-img" src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1200&q=80" alt="Healthy Lunch Banner" style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;opacity:1;transition:opacity 1s;" />
    <img class="banner-img" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRriSQ3_GTtJyFy0asw_AiuZBCEgn2Rh9iAPsdfVoJ490-VoFRuGeypW0IkU38ZyB7oK1s&usqp=CAU" alt="Lunch Box Variety" style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;opacity:0;transition:opacity 1s;" />
    <img class="banner-img" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQYsJSysXrolqr9-eMx5Y1l7V7qyXLJImboVLgtTADKwgYZxA6WicOJlU29BDgw70QFW54&usqp=CAU" alt="Nutritious Meal" style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;opacity:0;transition:opacity 1s;" />
  </div>
  <script>
    const images = document.querySelectorAll('.banner-img');
    let current = 0;
    setInterval(() => {
      images[current].style.opacity = 0;
      current = (current + 1) % images.length;
      images[current].style.opacity = 1;
    }, 4000);
  </script>

  <main>
    <?php
      if (file_exists('nutrition.php')) include 'nutrition.php';
      if (file_exists('menu.php')) include 'menu.php';
      if (file_exists('form.php')) include 'form.php';
    ?>

    <div style="display:flex; justify-content:center; align-items:center; margin:40px 0;">
      <a href="package.php" class="custom-btn" style="
        background:linear-gradient(90deg,#43ea6e 0%,#2dbd4f 100%);
        color:#fff;padding:18px 48px;font-size:1.5rem;font-weight:bold;border:none;border-radius:40px;
        box-shadow:0 4px 16px rgba(67,234,110,.2); text-decoration:none; transition:background .3s, transform .2s; letter-spacing:1px;"
        onmouseover="this.style.background='#2dbd4f'; this.style.transform='scale(1.05)';"
        onmouseout="this.style.background='linear-gradient(90deg, #43ea6e 0%, #2dbd4f 100%)'; this.style.transform='scale(1)'">
        Custom Order
      </a>
    </div>

    <?php
      if (file_exists('comment.php')) include 'comment.php';
      if (file_exists('testimonial.php')) include 'testimonial.php';
    ?>
  </main>

  <div class="footer">
    <p>&copy; 2025 Lunch Box Bangladesh. All rights reserved.</p>
    <p id="about">About Us: We provide healthy and affordable lunch to students and working men across Bangladesh with care and nutrition in mind.</p>
    <p id="contact">Emergency Contact: +8801784925341 | lunchboxbd@diu.com</p>
  </div>
</body>
</html>

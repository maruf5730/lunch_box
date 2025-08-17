<?php
// Show PHP errors on screen (remove these lines on production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// include db connection (same directory)
require_once __DIR__ . '/db.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic sanitation
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Insert using prepared statement
        $stmt = $conn->prepare("INSERT INTO contact (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $name, $email, $message);
        try {
            $stmt->execute();
            $success = 'Your message has been sent successfully!';
        } catch (Throwable $t) {
            $error = 'Database error: ' . $t->getMessage();
        }
        $stmt->close();
    }
}
?>

<?php
require_once __DIR__ . './navbar/init.php';
$active = 'contact';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Contact Us - Lunch Box</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    :root{--bg:#0f172a;--card:#0b1227;--muted:#94a3b8;--text:#e2e8f0;--primary:#22c55e;--accent:#38bdf8;--border:#1e293b;}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:var(--bg);color:var(--text)}
    nav{display:flex;justify-content:center;gap:20px;padding:14px;background:linear-gradient(90deg,var(--primary),var(--accent))}
    nav a{color:#fff;text-decoration:none;font-weight:700;padding:8px 14px;border-radius:6px}
    nav a:hover{background:rgba(255,255,255,.15)}
    main{max-width:760px;margin:auto;padding:28px}
    h1{margin-top:0}
    form{background:var(--card);padding:20px;border-radius:12px;border:1px solid var(--border);display:flex;flex-direction:column;gap:14px}
    input,textarea{padding:12px;border-radius:8px;border:1px solid var(--border);background:#1e293b;color:var(--text)}
    button{padding:12px;border:0;border-radius:8px;background:var(--primary);color:#fff;font-weight:700;cursor:pointer}
    button:hover{background:var(--accent)}
    .msg{margin:12px 0;padding:10px;border-radius:8px}
    .ok{background:#e6fffa;color:#065f46;border:1px solid #99f6e4}
    .bad{background:#fee2e2;color:#7f1d1d;border:1px solid #fecaca}
    .footer{margin-top:30px;text-align:center;padding:18px;background:linear-gradient(90deg,var(--primary),var(--accent));color:#fff}
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
  <main>
    <h1>Contact Us</h1>
    <p class="muted">We’d love to hear from you. Please send your message below.</p>

    <?php if ($success): ?>
      <div class="msg ok"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="msg bad"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <input type="text"   name="name"    placeholder="Your Name"  required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
      <input type="email"  name="email"   placeholder="Your Email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      <textarea name="message" rows="5" placeholder="Your Message" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
      <button type="submit">Send Message</button>
    </form>
  </main>

  <div class="footer">
    &copy; 2025 Lunch Box Bangladesh. All rights reserved.
  </div>
</body>
</html>

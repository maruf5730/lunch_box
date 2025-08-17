<?php
session_start();
/* ---------- DB CONNECTION (update only if your creds differ) ---------- */
$host = "127.0.0.1";
$db   = "lunch_box";
$user = "root";
$pass = "";
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
try { $pdo = new PDO($dsn, $user, $pass, $options); } 
catch (Exception $e) { die("DB connection failed: ".$e->getMessage()); }

/* ---------- IF ALREADY LOGGED IN ---------- */
if (!empty($_SESSION['user_id'])) {
  header("Location: index.php"); // create later if you want
  exit;
}

/* ---------- HANDLE LOGIN ---------- */
$msg = "";
if (isset($_GET['registered'])) {
  $msg = "Sign up successful! Please log in.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? "");
  $password = $_POST['password'] ?? "";

  if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === "") {
    $msg = "Enter a valid email and password.";
  } else {
    $stmt = $pdo->prepare("SELECT id, name, password_hash, user_type FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
      $_SESSION['user_id']   = $user['id'];
      $_SESSION['user_name'] = $user['name'];
      $_SESSION['user_type'] = $user['user_type'];
      header("Location: index.php"); // or index.php
      exit;
    } else {
      $msg = "Email or password is incorrect. If you don’t have an account, please sign up.";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login - Lunch Box</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body{font-family:system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#f6faf7;margin:0}
    .wrap{max-width:460px;margin:48px auto;background:#fff;padding:28px;border-radius:14px;box-shadow:0 8px 24px rgba(0,0,0,.08)}
    h1{margin:0 0 12px;color:#1f2937}
    p.small{color:#6b7280;margin-top:0}
    label{display:block;margin:12px 0 6px;color:#374151;font-weight:600}
    input{width:100%;padding:12px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:15px}
    button{margin-top:14px;width:100%;padding:12px 14px;background:#22c55e;border:0;border-radius:10px;color:#fff;
           font-weight:700;cursor:pointer}
    .info{background:#ecfeff;border:1px solid #a5f3fc;color:#155e75;padding:10px;border-radius:10px;margin-bottom:12px}
    .error{background:#fee2e2;border:1px solid #fecaca;color:#991b1b;padding:10px;border-radius:10px;margin-bottom:12px}
    a{color:#16a34a;text-decoration:none}
  </style>
</head>
<body>
  <div class="wrap">
    <h1>Login</h1>
    <p class="small">New user? <a href="signup.php">Create an account</a></p>

    <?php if ($msg): ?>
      <div class="<?= (isset($_GET['registered']) ? 'info' : 'error') ?>"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="on">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required value="<?=htmlspecialchars($_POST['email'] ?? '')?>">

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Log In</button>
    </form>
  </div>
</body>
</html>

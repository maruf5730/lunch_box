<?php
/* ---------- DB CONNECTION (update only if your creds differ) ---------- */
$host = "127.0.0.1";
$db   = "lunch_box";
$user = "root";
$pass = "";
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
try { $pdo = new PDO($dsn, $user, $pass, $options); } 
catch (Exception $e) { die("DB connection failed: ".$e->getMessage()); }

/* ---------- HANDLE SUBMIT ---------- */
$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name      = trim($_POST['name'] ?? "");
  $email     = trim($_POST['email'] ?? "");
  $password  = $_POST['password'] ?? "";
  $user_type = $_POST['user_type'] ?? "";

  // basic validation
  if ($name === "") $errors[] = "Name is required.";
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
  if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
  if (!in_array($user_type, ['school','college','university','adult'])) $errors[] = "Please select a valid user type.";

  if (empty($errors)) {
    // check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
      $errors[] = "This email is already registered. Please log in.";
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, user_type) VALUES (?,?,?,?)");
      $stmt->execute([$name, $email, $hash, $user_type]);
      // redirect to login with message
      header("Location: login.php?registered=1");
      exit;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sign Up - Lunch Box</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body{font-family:system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#f6faf7;margin:0}
    .wrap{max-width:460px;margin:48px auto;background:#fff;padding:28px;border-radius:14px;box-shadow:0 8px 24px rgba(0,0,0,.08)}
    h1{margin:0 0 12px;color:#1f2937}
    p.small{color:#6b7280;margin-top:0}
    label{display:block;margin:12px 0 6px;color:#374151;font-weight:600}
    input,select{width:100%;padding:12px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:15px}
    button{margin-top:14px;width:100%;padding:12px 14px;background:#22c55e;border:0;border-radius:10px;color:#fff;
           font-weight:700;cursor:pointer}
    .errors{background:#fee2e2;border:1px solid #fecaca;color:#991b1b;padding:10px;border-radius:10px;margin-bottom:12px}
    .hint{margin-top:10px;font-size:14px}
    a{color:#16a34a;text-decoration:none}
  </style>
</head>
<body>
  <div class="wrap">
    <h1>Create Account</h1>
    <p class="small">Already signed up? <a href="login.php">Go to Login</a></p>

    <?php if (!empty($errors)): ?>
      <div class="errors">
        <?php foreach ($errors as $e) echo "<div>• ".htmlspecialchars($e)."</div>"; ?>
      </div>
    <?php endif; ?>

    <form method="post" autocomplete="on">
      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" required value="<?=htmlspecialchars($_POST['name'] ?? '')?>">

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required value="<?=htmlspecialchars($_POST['email'] ?? '')?>">

      <label for="password">Password (min 6 chars)</label>
      <input type="password" id="password" name="password" required>

      <label for="user_type">User Type</label>
      <select id="user_type" name="user_type" required>
        <option value="" disabled selected>Select user type</option>
        <option value="school"     <?=(@$_POST['user_type']=='school'?'selected':'')?>>School</option>
        <option value="college"    <?=(@$_POST['user_type']=='college'?'selected':'')?>>College</option>
        <option value="university" <?=(@$_POST['user_type']=='university'?'selected':'')?>>University</option>
        <option value="adult"      <?=(@$_POST['user_type']=='adult'?'selected':'')?>>Adult</option>
      </select>

      <button type="submit">Sign Up</button>
    </form>

    <p class="hint">We securely store your password using hashing.</p>
  </div>
</body>
</html>

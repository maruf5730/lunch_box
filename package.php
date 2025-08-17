<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* --- Simple CSRF token --- */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(24));
}

$redirect = false;
$flash_success = null;
$flash_error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['package_error'] = "Invalid form token. Please refresh and try again.";
    } else {
        if (
            isset(
                $_POST['user_name'], $_POST['email'], $_POST['category'], $_POST['package_name'],
                $_POST['custom_meal'], $_POST['payment_method'], $_POST['payment_number']
            )
            && (($_POST['payment_method'] === 'Cash on Delivery') || !empty($_POST['transaction_id']))
        ) {
            $host = "localhost";
            $db = "lunch_box";
            $user = "root";
            $pass = "";

            $conn = @new mysqli($host, $user, $pass, $db);
            if ($conn->connect_error) {
                $_SESSION['package_error'] = "DB connection failed.";
            } else {
                $user_name       = trim($_POST['user_name']);
                $email           = trim($_POST['email']);
                $category        = trim($_POST['category']);
                $package         = trim($_POST['package_name']);
                $custom_meal     = trim($_POST['custom_meal']);
                $payment_method  = trim($_POST['payment_method']);
                $payment_number  = trim($_POST['payment_number']);
                $transaction_id  = ($payment_method === 'Cash on Delivery') ? '' : trim($_POST['transaction_id']);

                $stmt = $conn->prepare(
                    "INSERT INTO custom_pacakage 
                    (user_name, email, category, package_name, custom_meal, payment_method, payment_number, transaction_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
                );

                if ($stmt) {
                    $stmt->bind_param(
                        "ssssssss",
                        $user_name, $email, $category, $package, $custom_meal, $payment_method, $payment_number, $transaction_id
                    );
                    if ($stmt->execute()) {
                        $_SESSION['package_success'] = true;
                        $redirect = true;
                    } else {
                        $_SESSION['package_error'] = "Save failed: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $_SESSION['package_error'] = "Query prep failed.";
                }
                $conn->close();
            }
        } else {
            $_SESSION['package_error'] = "Please fill all required fields.";
        }
    }
}

if (!empty($_SESSION['package_success'])) {
    $flash_success = true;
    unset($_SESSION['package_success']);
}
if (!empty($_SESSION['package_error'])) {
    $flash_error = $_SESSION['package_error'];
    unset($_SESSION['package_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Meal Package Booking | Lunch Box</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg:#ffffff;         /* White background */
      --card:#f9f9f9;       /* Light grey card */
      --muted:#555555;      /* Grey text */
      --text:#000000;       /* Black text */
      --primary:#22c55e;    /* Green button */
      --ring:#16a34a;
      --border:#cccccc;     /* Light border */
      --danger:#ef4444;
      --accent:#38bdf8;
    }
    *{box-sizing:border-box}
    body{
      margin:0;
      font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial;
      color:var(--text);
      background: var(--bg);
      min-height:100vh;
    }

    .container{
      max-width: 1100px;
      margin: 0 auto;
      padding: 24px;
    }

    .nav{
      display:flex; align-items:center; justify-content:space-between;
      gap:12px; padding:12px 0;
    }
    .brand{
      display:flex; align-items:center; gap:10px; font-weight:700; letter-spacing:.2px;
    }
    .brand .logo{
      width:36px; height:36px; border-radius:10px;
      background: linear-gradient(135deg, var(--primary), var(--accent));
    }
    .btn{
      border:1px solid var(--border); color:var(--text);
      background:transparent; padding:10px 16px; border-radius:12px; font-weight:600;
      transition: all .2s ease; cursor:pointer;
    }
    .btn:hover{ border-color:var(--ring); background:#f0f0f0 }
    .btn-primary{
      background:linear-gradient(135deg, var(--primary), #16a34a);
      border:none; color:white;
    }
    .btn-primary:hover{ opacity:0.9 }

    .hero{
      margin-top:16px;
      background:#f3f3f3;
      border:1px solid var(--border);
      border-radius:20px; padding:28px;
      display:grid; grid-template-columns: 1.2fr 1fr; gap:24px;
    }
    @media (max-width: 900px){
      .hero{ grid-template-columns: 1fr }
    }
    .hero h1{
      margin:0 0 10px 0; font-size: clamp(24px, 2.5vw, 34px);
    }
    .hero p{ margin:0; color:var(--muted) }

    .card{
      background: var(--card);
      border:1px solid var(--border); border-radius:18px; padding:20px;
    }
    form{
      display:grid; grid-template-columns:1fr 1fr; gap:16px;
    }
    .full{ grid-column: 1 / -1 }
    label{
      display:block; margin:0 0 6px 2px; font-weight:600; color:var(--text); font-size:14px;
    }
    input, select, textarea{
      width:100%; border:1px solid var(--border); border-radius:12px;
      padding:12px 14px; background:#fff; color:var(--text);
      outline:none;
    }
    input:focus, select:focus, textarea:focus{
      border-color:var(--ring);
    }
    textarea{ min-height:110px; resize:vertical }

    .inline-help{ font-size:12px; color:var(--muted); margin-top:6px }

    .notice{
      padding:12px 14px; border-radius:12px; border:1px solid var(--border); margin-top:10px;
      background: #e6f4ff; color:#333; font-size:14px;
    }
    .footer-note{ color:var(--muted); font-size:13px; margin-top:10px }

    .modal{
      position: fixed; inset:0; display:flex; align-items:center; justify-content:center;
      background: rgba(0,0,0,.55); z-index: 999;
    }
    .modal-content{
      background: #fff; color:var(--text); border:1px solid var(--border);
      border-radius:16px; padding:22px; width: min(520px, 92vw);
    }
    .close-btn{ float:right; cursor:pointer; font-size:22px; color:#666 }
    .modal h3{ margin:0 0 6px 0 }
  </style>

  <?php if ($redirect): ?>
  <script>
    setTimeout(()=>{ window.location.href = 'index.php'; }, 1800);
  </script>
  <?php endif; ?>
</head>
<body>
  <div class="container">
    <div class="nav">
      <div class="brand">
        <div class="logo"></div>
        <div>Lunch Box</div>
      </div>
      <div class="row-actions">
        <a class="btn" href="index.php">Home</a>
        <a class="btn" href="menu.php">Daily Menu</a>
        <a class="btn" href="packages.php">Packages</a>
      </div>
    </div>

    <div class="hero">
      <div class="card">
        <h1>Online Meal Booking & Custom Order</h1>
        <p>Healthy, affordable meals for students and adults — delivered on schedule. Choose a package or tailor your own plan with specific daily items.</p>
        <div class="notice">
          Tip: If you select <b>Cash on Delivery</b>, you can skip the Transaction ID.
        </div>
        <p class="footer-note">Need help? Call: 01XXXXXXXXX or email: support@lunchbox.local</p>
      </div>

      <div class="card">
        <form method="POST" action="">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

          <div>
            <label for="user_name">Full Name</label>
            <input type="text" name="user_name" id="user_name" required placeholder="e.g. Jim Rahman">
          </div>

          <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required placeholder="you@example.com">
          </div>

          <div>
            <label for="category">Category</label>
            <select name="category" id="category" required>
              <option value="">Select category</option>
              <option value="Primary School Student">Primary School Student</option>
              <option value="High School Student">High School Student</option>
              <option value="University Student">University Student</option>
              <option value="Adult Men">Adult Men</option>
            </select>
          </div>

          <div>
            <label for="package_name">Package</label>
            <select name="package_name" id="package_name" required>
              <option value="">Select package</option>
              <option value="Daily">Daily</option>
              <option value="Weekly">Weekly</option>
              <option value="Monthly">Monthly</option>
            </select>
          </div>

          <div class="full">
            <label for="custom_meal">Custom Meal Preference</label>
            <textarea name="custom_meal" id="custom_meal" placeholder="E.g., Mon: Khichuri + Chicken | Tue: Veg Pulao + Egg Curry"></textarea>
            <div class="inline-help">Write your day-wise choices (optional). We’ll try to match it closely.</div>
          </div>

          <div>
            <label for="payment_method">Payment Method</label>
            <select name="payment_method" id="payment_method" required>
              <option value="">Select method</option>
              <option value="bKash">bKash</option>
              <option value="Nagad">Nagad</option>
              <option value="Rocket">Rocket</option>
              <option value="Cash on Delivery">Cash on Delivery</option>
            </select>
          </div>

          <div>
            <label for="payment_number">Payment Number</label>
            <input type="text" name="payment_number" id="payment_number" required placeholder="e.g. 01XXXXXXXXX">
          </div>

          <div class="full" id="trx-row">
            <label for="transaction_id">Transaction ID</label>
            <input type="text" name="transaction_id" id="transaction_id" placeholder="e.g. TX12345 (leave empty for COD)">
          </div>

          <div class="full" style="display:flex; gap:12px; flex-wrap:wrap; margin-top:4px">
            <button type="submit" class="btn btn-primary">Place Order</button>
            <a href="index.php" class="btn">Back to Home</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php if ($flash_success): ?>
    <div class="modal" id="successModal">
      <div class="modal-content">
        <span class="close-btn" onclick="document.getElementById('successModal').style.display='none'">&times;</span>
        <h3>🎉 Thank You!</h3>
        <p>Your order has been received. Our team will contact you as soon as possible.</p>
      </div>
    </div>
  <?php elseif (!empty($flash_error)): ?>
    <div class="modal" id="errorModal">
      <div class="modal-content">
        <span class="close-btn" onclick="document.getElementById('errorModal').style.display='none'">&times;</span>
        <h3 style="color:#ef4444;">⚠️ Error</h3>
        <p><?php echo htmlspecialchars($flash_error); ?></p>
      </div>
    </div>
  <?php endif; ?>

  <script>
    const methodEl = document.getElementById('payment_method');
    const trxRow   = document.getElementById('trx-row');
    const trxInput = document.getElementById('transaction_id');

    function toggleTxn(){
      const isCOD = methodEl.value === 'Cash on Delivery';
      trxInput.required = !isCOD;
      trxInput.placeholder = isCOD ? "Not required (Cash on Delivery)" : "e.g. TX12345";
      if(isCOD){ trxInput.value = ""; }
    }
    if(methodEl){
      methodEl.addEventListener('change', toggleTxn);
      toggleTxn();
    }
  </script>
</body>
</html>

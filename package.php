<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$redirect = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        isset($_POST['user_name'], $_POST['email'], $_POST['category'], $_POST['package_name'],
              $_POST['custom_meal'], $_POST['payment_method'], $_POST['payment_number'], $_POST['transaction_id'])
    ) {
        $host = "localhost";
        $db = "lunch_box";
        $user = "root";
        $pass = "";

        $conn = new mysqli($host, $user, $pass, $db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $user_name = $_POST['user_name'];
        $email = $_POST['email'];
        $category = $_POST['category'];
        $package = $_POST['package_name'];
        $custom_meal = $_POST['custom_meal'];
        $payment_method = $_POST['payment_method'];
        $payment_number = $_POST['payment_number'];
        $transaction_id = $_POST['transaction_id'];

        $stmt = $conn->prepare("INSERT INTO custom_pacakage 
        (user_name, email, category, package_name, custom_meal, payment_method, payment_number, transaction_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssssss", $user_name, $email, $category, $package, $custom_meal, $payment_method, $payment_number, $transaction_id);

        if ($stmt->execute()) {
            $_SESSION['package_success'] = true;
            $redirect = true;
        } else {
            $_SESSION['package_error'] = $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Meal Package Booking</title>
  <link rel="stylesheet" href="style.css">
  <style>
    form {
      max-width: 800px;
      margin: 40px auto;
      padding: 30px;
      background: #f9f9f9;
      border-radius: 12px;
      border: 1px solid #ccc;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
    }

    input, select, textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    button {
      padding: 12px 24px;
      background-color: #28a745;
      border: none;
      color: white;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
    }

    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 999;
    }

    .modal-content {
      background: white;
      padding: 30px;
      border-radius: 10px;
      width: 90%;
      max-width: 500px;
      margin: 10% auto;
      text-align: center;
    }

    .close-btn {
      float: right;
      cursor: pointer;
      font-size: 22px;
      color: #777;
    }
  </style>

  <?php if ($redirect): ?>
    <script>
      setTimeout(() => {
        window.location.href = 'index.php';
      }, 2000); // Redirect after 2 seconds
    </script>
  <?php endif; ?>
</head>
<body>

<h2 style="text-align:center; margin-top:30px;">Online Meal Booking & Custom Order</h2>

<form method="POST" action="">
  <label for="user_name">Full Name</label>
  <input type="text" name="user_name" required>

  <label for="email">Email</label>
  <input type="email" name="email" required>

  <label for="category">Category</label>
  <select name="category" required>
    <option value="Primary School Student">Primary School Student</option>
    <option value="High School Student">High School Student</option>
    <option value="University Student">University Student</option>
    <option value="Adult Men">Adult Men</option>
  </select>

  <label for="package_name">Package</label>
  <select name="package_name" required>
    <option value="Daily">Daily</option>
    <option value="Weekly">Weekly</option>
    <option value="Monthly">Monthly</option>
  </select>

  <label for="custom_meal">Custom Meal Preference</label>
  <textarea name="custom_meal" placeholder="Mention your daily meal choice from above menu (e.g. Monday: Khichuri, Chicken Curry)" rows="3"></textarea>

  <label for="payment_method">Payment Method</label>
  <select name="payment_method" required>
    <option value="bKash">bKash</option>
    <option value="Nagad">Nagad</option>
    <option value="Rocket">Rocket</option>
    <option value="Cash on Delivery">Cash on Delivery</option>
  </select>

  <label for="payment_number">Payment Number</label>
  <input type="text" name="payment_number" required>

  <label for="transaction_id">Transaction ID</label>
  <input type="text" name="transaction_id" required>

  <button type="submit">Place Order</button>
</form>

<!-- Success Modal -->
<?php if (!empty($_SESSION['package_success'])): ?>
  <div class="modal" id="successModal">
    <div class="modal-content">
      <span class="close-btn" onclick="document.getElementById('successModal').style.display='none'">&times;</span>
      <h3>Thank You!</h3>
      <p>Your order has been received. Our team will contact you as soon as possible.</p>
    </div>
  </div>
  <?php unset($_SESSION['package_success']); ?>
<?php elseif (!empty($_SESSION['package_error'])): ?>
  <div class="modal" id="errorModal">
    <div class="modal-content">
      <span class="close-btn" onclick="document.getElementById('errorModal').style.display='none'">&times;</span>
      <h3>Error!</h3>
      <p>Something went wrong: <?= htmlspecialchars($_SESSION['package_error']) ?></p>
    </div>
  </div>
  <?php unset($_SESSION['package_error']); ?>
<?php endif; ?>

</body>
</html>

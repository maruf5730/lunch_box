<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$showModal = false;
$success = false;
$errorMsg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "localhost";
    $db = "lunch_box";
    $user = "root";
    $pass = "";

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name = $_POST['name'];
    $email = $_POST['email'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("INSERT INTO comment (name, email, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $comment);

    if ($stmt->execute()) {
        $showModal = true;
        $success = true;
    } else {
        $showModal = true;
        $errorMsg = $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Clear session data
    session_unset();
    session_destroy();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Leave a Comment – Lunch Box</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .comment-btn {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 12px 20px;
      font-size: 1rem;
      border-radius: 8px;
      cursor: pointer;
      margin: 20px;
    }

    .modal {
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
      background-color: #fff;
      margin: 8% auto;
      padding: 25px;
      border: 1px solid #888;
      width: 90%;
      max-width: 500px;
      border-radius: 10px;
      text-align: left;
    }

    .modal-content input,
    .modal-content textarea {
      width: 100%;
      margin-bottom: 15px;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .modal-content button {
      width: 100%;
      padding: 10px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .close-btn {
      float: right;
      font-size: 20px;
      cursor: pointer;
      color: #999;
    }

    .comment-section {
      text-align: center;
      margin-top: 40px;
    }
  </style>
</head>
<body>
  <div class="comment-section">
    <h2>We Value Your Feedback</h2>
    <p>Leave your comment and help us improve our Lunch Box service.</p>
    <button class="comment-btn" onclick="openModal()">Leave a Comment</button>
  </div>

  <!-- Comment Modal -->
  <div id="commentModal" class="modal" style="display:none;">
    <div class="modal-content">
      <span class="close-btn" onclick="closeModal()">&times;</span>
      <h3>Leave a Comment</h3>
      <form method="POST">
        <label for="name">Name</label>
        <input type="text" name="name" required>

        <label for="email">Email</label>
        <input type="email" name="email" required>

        <label for="comment">Comment</label>
        <textarea name="comment" rows="5" required></textarea>

        <button type="submit">Submit Comment</button>
      </form>
    </div>
  </div>

  <!-- Success / Error Modal -->
  <?php if ($showModal): ?>
    <div id="successModal" class="modal" style="display:block;">
      <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h3><?= $success ? "Thank You!" : "Error!" ?></h3>
        <p><?= $success ? "Your comment has been submitted successfully." : "Something went wrong: " . htmlspecialchars($errorMsg) ?></p>
      </div>
    </div>
  <?php endif; ?>

  <!-- Modal JS -->
  <script>
    function openModal() {
      document.getElementById('commentModal').style.display = 'block';
    }

    function closeModal() {
      const modals = document.querySelectorAll('.modal');
      modals.forEach(modal => modal.style.display = 'none');
    }
  </script>
</body>
</html>

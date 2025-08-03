<?php
$host = "localhost";
$db = "lunch_box";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, comment FROM comment ORDER BY created_at DESC LIMIT 3";
$result = $conn->query($sql);
?>

<section style="background-color: #f9f9f9; padding: 40px 20px; text-align: center;">
  <h2 style="color: #333; margin-bottom: 30px;">Customer Testimonials</h2>
  <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px;">

    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()):
          $name = htmlspecialchars($row['name']);
          $comment = htmlspecialchars($row['comment']);
          $initials = strtoupper(substr($name, 0, 2)); // First 2 letters
      ?>
        <div style="width: 300px; background-color: white; border-radius: 10px; padding: 25px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); text-align: center;">
          <div style="width: 60px; height: 60px; background-color: #28a745; border-radius: 50%; color: white; font-weight: bold; font-size: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
            <?= $initials ?>
          </div>
          <p style="font-style: italic; color: #333;">“<?= $comment ?>”</p>
          <p style="font-weight: bold; color: #28a745; margin-top: 10px;">- <?= $name ?>, Valued Customer</p>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="color: #999;">No comments yet. Be the first to leave one!</p>
    <?php endif; ?>

  </div>
</section>

<?php $conn->close(); ?>

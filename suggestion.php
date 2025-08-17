<?php
session_start();
require_once __DIR__ . '/db.php'; // uses $pdo or $conn; we'll not query DB here, but keep for consistency

// Redirect if not logged in
if (empty($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

/* -------------------------------
   1) Targets by user_type (midpoints from your chart)
   school     -> Primary (6–11):   1600 kcal, 30g protein, 15g fiber
   college    -> High (12–17):     2000 kcal, 47.5g protein, 22.5g fiber
   university -> Univ (18–25):     2350 kcal, 62.5g protein, 27.5g fiber
   adult      -> Adult men (26–50):2600 kcal, 67.5g protein, 30g fiber
---------------------------------*/
$userType = $_SESSION['user_type'] ?? 'adult';
$targets = [
  'school'     => ['kcal'=>1600, 'protein'=>30,  'fiber'=>15],
  'college'    => ['kcal'=>2000, 'protein'=>47.5,'fiber'=>22.5],
  'university' => ['kcal'=>2350, 'protein'=>62.5,'fiber'=>27.5],
  'adult'      => ['kcal'=>2600, 'protein'=>67.5,'fiber'=>30],
];
$goal = $targets[$userType] ?? $targets['adult'];

/* -------------------------------
   2) Bangladeshi food list (per typical serving)
   NOTE: approximate values for guidance UI, not medical advice.
---------------------------------*/
$foods = [
  // name => [kcal, protein_g, fiber_g]
  'Plain Rice (1 cup)'       => [200, 4, 0.6],
  'Roti/Chapati (1 pc)'      => [110, 3, 2],
  'Khichuri (1 plate)'       => [400, 12, 6],
  'Vegetable Pulao (1 plate)'=> [380, 8, 3],
  'Lentil Dal (1 bowl)'      => [180, 10, 7],
  'Chicken Curry (1 serve)'  => [280, 24, 0],
  'Beef Curry (1 serve)'     => [350, 25, 0],
  'Fish Curry (1 serve)'     => [250, 22, 0],
  'Egg Curry (1 egg)'        => [180, 12, 0],
  'Mixed Vegetables (1 cup)' => [120, 4, 4],
  'Spinach (1 cup cooked)'   => [45, 5, 4],
  'Chickpeas/Chola (1 bowl)' => [180, 8, 8],
  'Salad (1 plate)'          => [60, 2, 3],
  'Curd/Yogurt (1 cup)'      => [100, 8, 0],
  'Milk (1 cup)'             => [120, 8, 0],
  'Banana (1 medium)'        => [100, 1, 3],
  'Apple (1 medium)'         => [95, 0, 4],
  'Tomato Bhorta (2 tbsp)'   => [60, 1, 1],
  'Potato Bhorta (1 scoop)'  => [150, 3, 2]
];

/* -------------------------------
   3) Handle form submission
---------------------------------*/
$consumed = ['kcal'=>0,'protein'=>0,'fiber'=>0];
$eaten = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item'])) {
  foreach ($_POST['item'] as $name => $qty) {
    $q = (float)$qty;
    if ($q > 0 && isset($foods[$name])) {
      $eaten[$name] = $q;
      $consumed['kcal']    += $foods[$name][0] * $q;
      $consumed['protein'] += $foods[$name][1] * $q;
      $consumed['fiber']   += $foods[$name][2] * $q;
    }
  }
}

// Remaining (not below zero)
$remaining = [
  'kcal'    => max(0, $goal['kcal']    - $consumed['kcal']),
  'protein' => max(0, $goal['protein'] - $consumed['protein']),
  'fiber'   => max(0, $goal['fiber']   - $consumed['fiber']),
];

/* -------------------------------
   4) Suggestion heuristic:
   - prefer items that add protein & fiber
   - avoid overshooting calories too much
---------------------------------*/
function scoreFood($food, $remain) {
  // Weighted score emphasizes protein & fiber per calorie
  [$k,$p,$f] = $food;
  if ($k <= 0) return 0;
  $proteinDensity = $p / $k;   // g per kcal
  $fiberDensity   = $f / $k;   // g per kcal

  // Need-based weights: if we still need a lot of protein/fiber, weight them more
  $wP = ($remain['protein'] > 10) ? 2.0 : 1.2;
  $wF = ($remain['fiber']   > 6)  ? 1.8 : 1.1;

  // penalty if calories are already low to target
  $calPenalty = ($remain['kcal'] < 350) ? 0.8 : 1.0;

  return ($proteinDensity * $wP + $fiberDensity * $wF) * $calPenalty;
}

$suggestions = [];
if (!empty($eaten)) {
  // Rank foods by score, excluding items already eaten heavily (>1.5 servings)
  $rank = [];
  foreach ($foods as $name => $vals) {
    $already = isset($eaten[$name]) ? $eaten[$name] : 0;
    if ($already >= 1.5) continue;
    $rank[$name] = scoreFood($vals, $remaining);
  }
  arsort($rank);

  // Pick top 3–5 items that won’t blow the remaining calories too much
  $limit = 5;
  $picked = 0;
  $calLeft = $remaining['kcal'];
  foreach ($rank as $name => $sc) {
    if ($picked >= $limit) break;
    $kcal = $foods[$name][0];
    // allow small overshoot (15%) if protein is still low
    $allow = ($remaining['protein'] > 10) ? $calLeft * 1.15 : $calLeft;
    if ($kcal <= max(250, $allow)) {
      $suggestions[] = $name;
      $picked++;
      $calLeft -= $kcal;
    }
  }
}
?>

<?php
require_once __DIR__ . './navbar/init.php';
$active = 'suggestion';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Food Suggestions - Lunch Box</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    :root{--bg:#0f172a;--card:#0b1227;--muted:#94a3b8;--text:#e2e8f0;--primary:#22c55e;--accent:#38bdf8;--border:#1e293b;}
    body{margin:0;font-family:Arial,Helvetica,sans-serif;background:var(--bg);color:var(--text)}
    nav{display:flex;justify-content:center;gap:20px;padding:14px;background:linear-gradient(90deg,var(--primary),var(--accent))}
    nav a{color:#fff;text-decoration:none;font-weight:700;padding:8px 14px;border-radius:6px}
    nav a:hover{background:rgba(255,255,255,.15)}
    .wrap{max-width:1000px;margin:auto;padding:24px}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
    .card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:18px}
    h2,h3{margin-top:0}
    table{width:100%;border-collapse:collapse;margin-top:10px}
    th,td{border:1px solid var(--border);padding:8px;text-align:left}
    th{background:#123}
    input[type=number]{width:80px;padding:6px;border-radius:8px;border:1px solid var(--border);background:#1e293b;color:var(--text)}
    .btn{display:inline-block;background:var(--primary);border:0;border-radius:10px;color:#fff;font-weight:700;padding:10px 14px;cursor:pointer}
    .btn:hover{background:var(--accent)}
    .pill{display:inline-block;padding:6px 10px;border-radius:999px;border:1px solid var(--border);background:#132; margin-right:6px}
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
  <div class="wrap">
    <h2>Smart Food Suggestions (Based on Your Daily Target)</h2>
    <p class="pill">User type: <strong><?= htmlspecialchars($userType) ?></strong></p>
    <p class="pill">Daily Target: <?= $goal['kcal'] ?> kcal • <?= $goal['protein'] ?>g protein • <?= $goal['fiber'] ?>g fiber</p>

    <div class="grid">
      <!-- Left: Input form -->
      <div class="card">
        <h3>1) Select What You Already Ate</h3>
        <form method="post">
          <table>
            <thead>
              <tr>
                <th>Food (BD)</th>
                <th>kcal</th>
                <th>Protein (g)</th>
                <th>Fiber (g)</th>
                <th>Qty</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($foods as $name => $vals): ?>
                <tr>
                  <td><?= htmlspecialchars($name) ?></td>
                  <td><?= $vals[0] ?></td>
                  <td><?= $vals[1] ?></td>
                  <td><?= $vals[2] ?></td>
                  <td><input type="number" step="0.5" min="0" name="item[<?= htmlspecialchars($name) ?>]" value="<?= isset($eaten[$name]) ? htmlspecialchars($eaten[$name]) : '0' ?>"></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <br>
          <button class="btn" type="submit">Calculate & Suggest</button>
        </form>
      </div>

      <!-- Right: Result & Suggestions -->
      <div class="card">
        <h3>2) Your Progress Today</h3>
        <p>
          Consumed: <strong><?= round($consumed['kcal']) ?> kcal</strong>,
          Protein <strong><?= round($consumed['protein'],1) ?>g</strong>,
          Fiber <strong><?= round($consumed['fiber'],1) ?>g</strong>
        </p>
        <p>
          Remaining: <strong><?= round($remaining['kcal']) ?> kcal</strong>,
          Protein <strong><?= round($remaining['protein'],1) ?>g</strong>,
          Fiber <strong><?= round($remaining['fiber'],1) ?>g</strong>
        </p>

        <h3>3) Suggested Next Foods</h3>
        <?php if (!empty($eaten)): ?>
          <?php if ($remaining['kcal'] <= 50 && $remaining['protein'] < 5 && $remaining['fiber'] < 3): ?>
            <p>Great! You’ve met your targets for today 🎉 Consider a light snack like <em>Salad</em> or <em>Fruit</em> if hungry.</p>
          <?php else: ?>
            <ul>
              <?php
              if (empty($suggestions)) {
                // fallback suggestions based on needs
                if ($remaining['protein'] > 12)      $suggestions = ['Chicken Curry (1 serve)','Fish Curry (1 serve)','Lentil Dal (1 bowl)'];
                elseif ($remaining['fiber'] > 6)     $suggestions = ['Chickpeas/Chola (1 bowl)','Mixed Vegetables (1 cup)','Spinach (1 cup cooked)'];
                else                                  $suggestions = ['Roti/Chapati (1 pc)','Salad (1 plate)','Apple (1 medium)'];
              }
              foreach ($suggestions as $s) {
                [$k,$p,$f] = $foods[$s];
                echo "<li><strong>".htmlspecialchars($s)."</strong> — {$k} kcal, {$p}g protein, {$f}g fiber</li>";
              }
              ?>
            </ul>
            <p style="color:#94a3b8">Tip: We’ve prioritized foods rich in <strong>protein & fiber</strong> to keep you full and balanced.</p>
          <?php endif; ?>
        <?php else: ?>
          <p>Please enter what you already ate today to get personalized suggestions.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>

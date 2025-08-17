<?php
// navbar.php
$active = $active ?? ''; // 'home' | 'suggestion' | 'about' | 'contact'
?>
<style>
  :root{--bg:#0f172a;--card:#0b1227;--muted:#94a3b8;--text:#e2e8f0;--primary:#22c55e;--accent:#38bdf8;--border:#1e293b;}
  nav{display:flex;align-items:center;gap:12px;padding:12px 16px;background:linear-gradient(90deg,var(--primary),var(--accent));border-bottom:2px solid var(--border);position:sticky;top:0;z-index:50}
  nav .left,nav .right{display:flex;align-items:center;gap:10px}
  nav a{color:#fff;text-decoration:none;font-weight:700;padding:8px 12px;border-radius:8px}
  nav a:hover{background:rgba(255,255,255,.15)}
  nav a.active{background:rgba(0,0,0,.22);border:1px solid rgba(255,255,255,.25)}
  nav .spacer{flex:1}
  .user-badge{display:flex;align-items:center;gap:8px;padding:6px 10px;border-radius:999px;background:rgba(0,0,0,.18);color:#fff;font-weight:600;border:1px solid rgba(255,255,255,.2)}
  .user-icon{width:28px;height:28px;border-radius:50%;background:#ffffff22;display:grid;place-items:center;border:1px solid #ffffff44}
  .user-icon svg{width:18px;height:18px;fill:#fff;opacity:.9}
</style>
<style>
  :root {
    --bg:#0f172a;
    --card:#0b1227;
    --muted:#94a3b8;
    --text:#e2e8f0;
    --primary:#22c55e;
    --accent:#38bdf8;
    --border:#1e293b;
    --active:#ffcc00; /* highlight color for active link */
  }

  nav {
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px 16px;
    background:linear-gradient(90deg,var(--primary),var(--accent));
    border-bottom:2px solid var(--border);
    position:sticky;
    top:0;
    z-index:50
  }

  nav .left, nav .right {
    display:flex;
    align-items:center;
    gap:10px
  }

  nav a {
    color:#fff;
    text-decoration:none;
    font-weight:700;
    padding:8px 12px;
    border-radius:8px;
    transition:all 0.25s ease;
  }

  nav a:hover {
    background:rgba(255,255,255,.15)
  }

  /* 🔥 Active link highlight */
  nav a.active {
    background:var(--active);
    color:#000;  /* black text for contrast */
    border:1px solid #000;
    box-shadow:0 0 8px rgba(0,0,0,.3);
  }

  nav .spacer {flex:1}

  .user-badge {
    display:flex;
    align-items:center;
    gap:8px;
    padding:6px 10px;
    border-radius:999px;
    background:rgba(0,0,0,.18);
    color:#fff;
    font-weight:600;
    border:1px solid rgba(255,255,255,.2)
  }

  .user-icon {
    width:28px;height:28px;
    border-radius:50%;
    background:#ffffff22;
    display:grid;
    place-items:center;
    border:1px solid #ffffff44
  }

  .user-icon svg {
    width:18px;height:18px;
    fill:#fff;
    opacity:.9
  }
</style>


<nav>
  <div class="left">
    <a href="index.php" class="<?= $active==='home'?'active':'' ?>">Home</a>
    <a href="suggestion.php" class="<?= $active==='suggestion'?'active':'' ?>">Suggestions</a>
    <a href="aboutus.php" class="<?= $active==='about'?'active':'' ?>">About Us</a>
    <a href="contact.php" class="<?= $active==='contact'?'active':'' ?>">Contact</a>
  </div>
  <div class="spacer"></div>
  <div class="right">
    <?php if (!empty($_SESSION['user_id'])): ?>
      <span class="user-badge" title="<?= htmlspecialchars($_SESSION['user_type'] ?? '') ?>">
        <span class="user-icon">
          <svg viewBox="0 0 24 24"><path d="M12 12c2.76 0 5-2.46 5-5.5S14.76 1 12 1 7 3.46 7 6.5 9.24 12 12 12zm0 2c-4.42 0-8 2.91-8 6.5V22h16v-1.5c0-3.59-3.58-6.5-8-6.5z"/></svg>
        </span>
        <?= current_user_name(); ?>
      </span>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="signup.php">Sign Up</a>
    <?php endif; ?>
  </div>
</nav>

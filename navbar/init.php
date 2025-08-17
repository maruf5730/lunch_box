<?php
// init.php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// (optional) load DB connection once for all pages
if (file_exists(__DIR__ . '/db.php')) {
  require_once __DIR__ . '/db.php';
}

// Helper: get current user name safely
function current_user_name() {
  return htmlspecialchars($_SESSION['user_name'] ?? 'User');
}

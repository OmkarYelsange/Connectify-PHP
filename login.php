<?php
require_once __DIR__ . '/includes/auth.php';
if (!empty($_SESSION['user_id'])) { header('Location: dashboard.php'); exit; }
$error = '';
$login = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $login = trim((string)($_POST['login'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $stmt = $pdo->prepare("SELECT user_id, full_name, username, password_hash, status, role, profile_photo FROM users WHERE email = ? OR username = ? LIMIT 1");
    $stmt->execute([strtolower($login), strtolower($login)]);
    $user = $stmt->fetch();
    if ($user && $user['status'] === 'active' && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['user_id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'] ?? 'user';
        $_SESSION['profile_photo'] = $user['profile_photo'] ?? null;
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $pdo->prepare('UPDATE users SET last_seen = NOW() WHERE user_id = ?')->execute([$user['user_id']]);
        header('Location: dashboard.php'); exit;
    }
    $error = 'Login details are incorrect. Please try again.';
}
$pageTitle = 'Log in';
require __DIR__ . '/includes/header.php';
?>
<section class="auth-layout">
  <div class="auth-intro">
    <div class="eyebrow">LESS SCROLLING, MORE TALKING</div>
    <h1>Your conversations.<br><span>Your community.</span></h1>
    <p>A clean, comfortable place to catch up and keep in touch.</p>
    <div class="message-preview"><div class="preview-avatar">A</div><div><strong>Alex Morgan</strong><p>Hey! How did your presentation go? ✨</p></div><span class="online-dot"></span></div>
  </div>
  <div class="auth-card">
    <div class="card-icon">↗</div><h2>Welcome back</h2><p class="muted">Log in to continue your conversations.</p>
    <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="form-stack">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <label>Username or email<input name="login" value="<?= e($login) ?>" required autocomplete="username" placeholder="Enter username or email"></label>
      <label>Password<input type="password" name="password" required autocomplete="current-password" placeholder="Enter your password"></label>
      <button class="button button-primary button-wide" type="submit">Log in <span>→</span></button>
    </form>
    <p class="auth-switch">New to ChatApp? <a href="register.php">Create an account</a></p>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>

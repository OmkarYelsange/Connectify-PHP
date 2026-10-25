<?php
require_once __DIR__ . '/includes/auth.php';
if (!empty($_SESSION['user_id'])) { header('Location: dashboard.php'); exit; }
$errors = [];
$fullName = $username = $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $fullName = trim((string)($_POST['full_name'] ?? ''));
    $username = strtolower(trim((string)($_POST['username'] ?? '')));
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $password = (string)($_POST['password'] ?? '');
    if (mb_strlen($fullName) < 2 || mb_strlen($fullName) > 100) $errors[] = 'Full name must be between 2 and 100 characters.';
    if (!preg_match('/^[a-z0-9_]{3,40}$/', $username)) $errors[] = 'Username must be 3–40 characters (letters, numbers, underscore).';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (strlen($password) < 8) $errors[] = 'Password must contain at least 8 characters.';
    if (!$errors) {
        $stmt = $pdo->prepare('SELECT user_id FROM users WHERE username = ? OR email = ? LIMIT 1');
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) $errors[] = 'That username or email is already registered.';
    }
    if (!$errors) {
        $colors = ['#7c3aed', '#2563eb', '#db2777', '#0891b2', '#ea580c'];
        $color = $colors[random_int(0, count($colors) - 1)];
        $stmt = $pdo->prepare('INSERT INTO users (full_name, username, email, password_hash, profile_color) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$fullName, $username, $email, password_hash($password, PASSWORD_DEFAULT), $color]);
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$pdo->lastInsertId();
        $_SESSION['full_name'] = $fullName;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'user';
        $_SESSION['profile_photo'] = null;
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        header('Location: dashboard.php'); exit;
    }
}
$pageTitle = 'Create account';
require __DIR__ . '/includes/header.php';
?>
<section class="auth-layout">
  <div class="auth-intro">
    <div class="eyebrow">YOUR PEOPLE, ONE PLACE</div>
    <h1>Good conversations<br><span>start here.</span></h1>
    <p>Create your account and connect with classmates, friends and your community.</p>
    <div class="feature-pills"><span>Private chats</span><span>Simple & fast</span><span>Made for everyone</span></div>
  </div>
  <div class="auth-card">
    <div class="card-icon">✦</div><h2>Create your account</h2><p class="muted">Join ChatApp in less than a minute.</p>
    <?php foreach ($errors as $error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endforeach; ?>
    <form method="post" class="form-stack">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <label>Full name<input name="full_name" value="<?= e($fullName) ?>" maxlength="100" required autocomplete="name" placeholder="Your name"></label>
      <label>Username<input name="username" value="<?= e($username) ?>" maxlength="40" required autocomplete="username" placeholder="e.g. rohan_21"><small>3–40 letters, numbers or underscores.</small></label>
      <label>Email address<input type="email" name="email" value="<?= e($email) ?>" required autocomplete="email" placeholder="you@example.com"></label>
      <label>Password<input type="password" name="password" minlength="8" required autocomplete="new-password" placeholder="At least 8 characters"></label>
      <button class="button button-primary button-wide" type="submit">Create account <span>→</span></button>
    </form>
    <p class="auth-switch">Already have an account? <a href="login.php">Log in</a></p>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>

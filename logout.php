<?php
require_once __DIR__ . '/includes/auth.php';
if (!empty($_SESSION['user_id'])) $pdo->prepare('UPDATE users SET last_seen=NOW() WHERE user_id=?')->execute([current_user_id()]);
$_SESSION=[]; if (ini_get('session.use_cookies')) { $p=session_get_cookie_params(); setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']); } session_destroy(); header('Location: index.php'); exit;

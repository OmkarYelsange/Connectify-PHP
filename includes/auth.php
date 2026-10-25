<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/../config/database.php';
function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function csrf_token(): string { if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); return $_SESSION['csrf_token']; }
function verify_csrf(): void { $sent = $_POST['csrf_token'] ?? ''; if (!is_string($sent) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $sent)) { http_response_code(403); exit('Invalid request token. Refresh and try again.'); } }
function verify_api_csrf(): void { $sent = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ''; if (!is_string($sent) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $sent)) { http_response_code(403); header('Content-Type: application/json'); echo json_encode(['error'=>'Security token expired. Refresh the page.']); exit; } }
function require_login(): void { if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; } }
function current_user_id(): int { return (int)($_SESSION['user_id'] ?? 0); }
function is_member(PDO $pdo, int $conversationId, int $userId): bool { $s=$pdo->prepare('SELECT 1 FROM conversation_members WHERE conversation_id=? AND user_id=?'); $s->execute([$conversationId,$userId]); return (bool)$s->fetchColumn(); }
function require_admin(): void { if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? 'user') !== 'admin') { http_response_code(403); exit('Administrator access required. <a href="../dashboard.php">Back to ChatApp</a>'); } }
function json_response(array $data, int $status=200): never { http_response_code($status); header('Content-Type: application/json; charset=utf-8'); header('Cache-Control: no-store'); echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE); exit; }

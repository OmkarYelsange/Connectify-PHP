<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php'); exit; }
verify_csrf();
$me = current_user_id();
$other = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
if (!$other || $other === $me) { header('Location: dashboard.php'); exit; }
$stmt = $pdo->prepare("SELECT user_id FROM users WHERE user_id = ? AND status = 'active'");
$stmt->execute([$other]);
if (!$stmt->fetch()) { header('Location: dashboard.php'); exit; }

$stmt = $pdo->prepare("SELECT mine.conversation_id FROM conversation_members mine JOIN conversation_members theirs ON theirs.conversation_id = mine.conversation_id JOIN conversations c ON c.conversation_id=mine.conversation_id WHERE mine.user_id = ? AND theirs.user_id = ? AND c.conversation_type='private' LIMIT 1");
$stmt->execute([$me, $other]);
$conversationId = $stmt->fetchColumn();
if (!$conversationId) {
    try {
        $pdo->beginTransaction();
        $create = $pdo->prepare("INSERT INTO conversations (conversation_type, created_by) VALUES ('private', ?)");
        $create->execute([$me]);
        $conversationId = (int)$pdo->lastInsertId();
        $insert = $pdo->prepare('INSERT INTO conversation_members (conversation_id, user_id) VALUES (?, ?), (?, ?)');
        $insert->execute([$conversationId, $me, $conversationId, $other]);
        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        http_response_code(500); exit('Could not start chat. Please try again.');
    }
}
header('Location: chat.php?id=' . (int)$conversationId);
exit;

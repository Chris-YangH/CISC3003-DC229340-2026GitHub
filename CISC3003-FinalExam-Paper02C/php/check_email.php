<?php
declare(strict_types=1);
require_once __DIR__ . '/../connect.php';

header('Content-Type: application/json');
$email = trim((string) ($_GET['email'] ?? ''));

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['valid' => false, 'available' => false, 'message' => 'Invalid email format.']);
    exit;
}

$stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$available = $stmt->get_result()->num_rows === 0;

echo json_encode([
    'valid' => true,
    'available' => $available,
    'message' => $available ? 'Email is available.' : 'Email is already registered.',
]);
?>

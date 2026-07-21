<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$phone    = input('phone');
$password = input('password');

if (!$phone || !$password) sendError('ফোন এবং পাসওয়ার্ড দিন');

$db   = getDB();
$stmt = $db->prepare("SELECT * FROM users WHERE phone = ? AND is_active = 1");
$stmt->execute([$phone]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    sendError('ফোন নম্বর বা পাসওয়ার্ড ভুল');
}

$token = jwtEncode([
    'id'        => $user['id'],
    'phone'     => $user['phone'],
    'user_type' => $user['user_type'],
]);

// LoginActivity expects: json.getJSONObject("user") — NO "data" wrapper
// So we echo directly, not via sendSuccess()
http_response_code(200);
echo json_encode([
    'success' => true,
    'message' => 'লগইন সফল',
    'token'   => $token,
    'user'    => [
        'id'        => (string)$user['id'],
        'name'      => $user['name'],
        'phone'     => $user['phone'],
        'address'   => $user['address'] ?? '',
        'union_name'=> $user['union_name'] ?? '',
        'image'     => basename($user['image'] ?? 'default.png'),  // filename only
        'user_type' => $user['user_type'],
    ],
], JSON_UNESCAPED_UNICODE);

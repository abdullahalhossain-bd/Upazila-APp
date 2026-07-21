<?php
// =====================================================
// admin_login.php  —  Phase 6
// POST phone, password
// Validates against users where user_type IN (admin, uno, ict, developer)
// Returns JWT + user object including role_key + role_name (Bangla)
// 403 if user_type is 'user' (regular user not allowed)
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$phone    = input('phone');
$password = input('password');

if (!$phone || !$password) sendError('ফোন এবং পাসওয়ার্ড দিন');

$db   = getDB();
// LEFT JOIN admin_roles to also cover legacy 'admin' users (which have no role_key row)
$stmt = $db->prepare("
    SELECT u.*, r.role_name
    FROM users u
    LEFT JOIN admin_roles r ON r.role_key = u.user_type
    WHERE u.phone = ? AND u.is_active = 1
    LIMIT 1
");
$stmt->execute([$phone]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    sendError('ফোন নম্বর বা পাসওয়ার্ড ভুল', 401);
}

$type = $user['user_type'] ?? 'user';
if (!in_array($type, ['admin', 'uno', 'ict', 'developer'], true)) {
    sendError('এই প্যানেল শুধুমাত্র অ্যাডমিনদের জন্য। সাধারণ ইউজার অ্যাপ ব্যবহার করুন।', 403);
}

// Build role_name fallback for legacy 'admin' users (no admin_roles row)
$role_name = $user['role_name'];
if (!$role_name) {
    $role_name = match ($type) {
        'admin'     => 'অ্যাডমিন',
        'uno'       => 'উপজেলা নির্বাহী অফিসার',
        'ict'       => 'উপজেলা আইসিটি অফিসার',
        'developer' => 'অ্যাপ ডেভেলপার',
        default     => 'অ্যাডমিন',
    };
}

$token = jwtEncode([
    'id'        => (int)$user['id'],
    'phone'     => $user['phone'],
    'user_type' => $type,
]);

// Best-effort audit log
logAdminAction(['id' => $user['id']], 'login', 'auth', $user['id'], "Admin login: $type");

// Response shape mirrors login.php + adds role_key + role_name.
http_response_code(200);
echo json_encode([
    'success'  => true,
    'message'  => 'অ্যাডমিন লগইন সফল',
    'token'    => $token,
    'user'     => [
        'id'        => (string)$user['id'],
        'name'      => $user['name'],
        'phone'     => $user['phone'],
        'address'   => $user['address']   ?? '',
        'union_name'=> $user['union_name'] ?? '',
        'image'     => basename($user['image'] ?? 'default.png'),   // filename only — app prepends Config.IMAGE_URL
        'image_url' => imageUrl($user['image'] ?? 'default.png'),   // full URL for any admin UI that wants it
        'user_type' => $type,
        'role_key'  => $type === 'admin' ? 'admin' : $type,
        'role_name' => $role_name,
    ],
], JSON_UNESCAPED_UNICODE);

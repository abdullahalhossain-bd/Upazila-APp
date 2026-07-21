<?php
// profile.php — SECURITY FIXED: requires JWT auth, owner-or-admin check (IDOR fix)
require_once 'config/config.php';

// CRITICAL FIX: Require authentication
$auth = authUser();

// ProfileFragment checks: json.optString("status") === "success"
// So response must be: {"status":"success","data":{...}}

// Use authenticated user's ID by default; allow admin to view others
$user_id = input('user_id') ?: (string)$auth['id'];
if (!$user_id) $user_id = (string)$auth['id'];

// Authorization: only self or admin can view profile
$isAdmin = in_array($auth['user_type'] ?? '', ['admin','uno','ict','developer'], true);
if ((int)$user_id !== (int)$auth['id'] && !$isAdmin) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Forbidden']);
    exit();
}

$db   = getDB();
$stmt = $db->prepare("SELECT id, name, phone, address, union_name, image, user_type FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'ব্যবহারকারী পাওয়া যায়নি']);
    exit();
}

// ProfileFragment: Config.IMAGE_URL + image → filename only
$user['image'] = basename($user['image'] ?? 'default.png');

http_response_code(200);
echo json_encode([
    'status' => 'success',   // ProfileFragment checks optString("status") === "success"
    'data'   => $user,
], JSON_UNESCAPED_UNICODE);

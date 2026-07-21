<?php
// =====================================================
// admin_update_user_role.php  —  Phase 6
// POST — developer ONLY  (only App Developer can change roles)
// Required: user_id, user_type (one of: user, admin, uno, ict, developer)
// Returns {success:true, message:"..."}
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$admin = requireRole('developer');

$user_id   = input('user_id');
$user_type = input('user_type');

if (!$user_id)                    sendError('User ID দিন', 400);
if (!$user_type)                  sendError('user_type দিন', 400);

$allowed_types = ['user', 'admin', 'uno', 'ict', 'developer'];
if (!in_array($user_type, $allowed_types, true)) {
    sendError('user_type অবৈধ — user|admin|uno|ict|developer', 400);
}

$db = getDB();

// Verify target exists
$check = $db->prepare("SELECT id, user_type FROM users WHERE id = ?");
$check->execute([$user_id]);
$target = $check->fetch();
if (!$target) sendError('ব্যবহারকারী পাওয়া যায়নি', 404);

// Safety: developer cannot demote themselves (prevents lockout)
if ((int)$user_id === (int)($admin['id'] ?? 0) && $user_type !== 'developer') {
    sendError('নিজের রোল পরিবর্তন করা যাবে না (লকআউট প্রতিরোধ)', 400);
}

$db->prepare("UPDATE users SET user_type = ? WHERE id = ?")
   ->execute([$user_type, $user_id]);

logAdminAction($admin, 'update', 'user_role', (int)$user_id,
               "role={$target['user_type']}→$user_type");

sendSuccess(null, 'ব্যবহারকারীর রোল আপডেট হয়েছে');

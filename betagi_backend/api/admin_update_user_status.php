<?php
// =====================================================
// admin_update_user_status.php  —  Phase 6
// POST — admin only (uno OR developer)
// Required: user_id, is_active (0 or 1)
// Returns {success:true, message:"..."}
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$admin = requireRole('uno', 'developer');

$user_id   = input('user_id');
$is_active = input('is_active');

if (!$user_id) sendError('User ID দিন', 400);
if ($is_active === '') sendError('is_active (0 বা 1) দিন', 400);

// Coerce to int 0/1
$is_active_int = (int)$is_active;
if (!in_array($is_active_int, [0, 1], true)) sendError('is_active অবশ্যই 0 বা 1 হতে হবে', 400);

$db = getDB();

// Prevent admin from deactivating themselves
if ((int)$user_id === (int)($admin['id'] ?? 0) && $is_active_int === 0) {
    sendError('নিজের অ্যাকাউন্ট নিষ্ক্রিয় করা যাবে না', 400);
}

// Verify target exists
$check = $db->prepare("SELECT id, user_type FROM users WHERE id = ?");
$check->execute([$user_id]);
$target = $check->fetch();
if (!$target) sendError('ব্যবহারকারী পাওয়া যায়নি', 404);

$db->prepare("UPDATE users SET is_active = ? WHERE id = ?")
   ->execute([$is_active_int, $user_id]);

logAdminAction($admin, 'update', 'user_status', (int)$user_id,
               "is_active=$is_active_int (target type={$target['user_type']})");

sendSuccess(null, $is_active_int ? 'ব্যবহারকারী সক্রিয় করা হয়েছে' : 'ব্যবহারকারী নিষ্ক্রিয় করা হয়েছে');

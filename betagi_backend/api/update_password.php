<?php
// update_password.php — Fixed: requires JWT auth, uses token user_id, rate limited
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

// CRITICAL FIX: Require JWT authentication — no more client-supplied user_id
$auth = authUser();
$user_id = (string)$auth['id'];

$old_pass = input('old_password');
$new_pass = input('new_password');
$confirm  = input('confirm_password');

if (!$old_pass || !$new_pass) sendError('পাসওয়ার্ড দিন');
if ($new_pass !== $confirm) sendError('নতুন পাসওয়ার্ড মিলছে না');

// Password strength validation
$pwCheck = validatePasswordStrength($new_pass);
if (!$pwCheck['valid']) sendError($pwCheck['message']);

// Prevent same password
if ($old_pass === $new_pass) sendError('নতুন পাসওয়ার্ড পুরোনোটির মতো হতে পারে না');

// Rate limit: max 5 password change attempts per hour
if (!rateLimitCheck('pwchange_' . $user_id, 'password_change', 5, 3600)) {
    sendError('অনেকবার চেষ্টা করেছেন। ১ ঘণ্টা পরে আবার চেষ্টা করুন', 429);
}

$db   = getDB();
$stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || !password_verify($old_pass, $user['password'])) {
    sendError('পুরনো পাসওয়ার্ড ভুল');
}

$db->prepare("UPDATE users SET password = ? WHERE id = ?")
   ->execute([password_hash($new_pass, PASSWORD_BCRYPT), $user_id]);

sendSuccess(null, 'পাসওয়ার্ড পরিবর্তন হয়েছে');

<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$auth    = authUser();
$user_id = input('user_id', $auth['id']);

// SECURITY FIX: Use strict comparison + include all admin roles
$isAdmin = in_array($auth['user_type'] ?? '', ['admin','uno','ict','developer'], true);
if ((int)$auth['id'] !== (int)$user_id && !$isAdmin) {
    sendError('Forbidden', 403);
}

$name     = input('name');
$phone    = input('phone');
$address  = input('address');
$imageB64 = $_POST['image'] ?? '';

if (!$name) sendError('নাম দিন');

$db = getDB();

// Check phone uniqueness if changed
if ($phone) {
    $stmt = $db->prepare("SELECT id FROM users WHERE phone = ? AND id != ?");
    $stmt->execute([$phone, $user_id]);
    if ($stmt->fetch()) sendError('এই ফোন নম্বর অন্য একজনের');
}

if ($imageB64) {
    $image = saveBase64Image($imageB64, 'profiles');
    $stmt = $db->prepare("UPDATE users SET name=?, phone=?, address=?, image=? WHERE id=?");
    $stmt->execute([$name, $phone, $address, $image, $user_id]);
} else {
    $stmt = $db->prepare("UPDATE users SET name=?, phone=?, address=? WHERE id=?");
    $stmt->execute([$name, $phone, $address, $user_id]);
}

// Return updated user
$stmt = $db->prepare("SELECT id, name, phone, address, union_name, image, user_type FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$user['image'] = imageUrl($user['image']);

sendSuccess($user, 'প্রোফাইল আপডেট হয়েছে');

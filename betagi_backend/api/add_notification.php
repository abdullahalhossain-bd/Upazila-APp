<?php
// =====================================================
// add_notification.php  —  Phase 6
// POST — admin only (uno OR ict OR developer)
// Required: title, body
// Optional: type (default 'general')
// Returns {success:true, message:"...", data:{id}} (201)
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$admin = requireRole('uno', 'ict', 'developer');

$title = input('title');
$body  = input('body');
$type  = input('type', 'general');

if (!$title) sendError('শিরোনাম দিন', 400);
if (!$body)  sendError('বিবরণ দিন', 400);

$db   = getDB();
$stmt = $db->prepare("INSERT INTO notifications (title, body, type, sent_by) VALUES (?, ?, ?, ?)");
$stmt->execute([$title, $body, $type, $admin['id'] ?? null]);
$id   = $db->lastInsertId();

logAdminAction($admin, 'create', 'notification', (int)$id,
               "type=$type; title=" . mb_substr($title, 0, 200));

sendSuccess(['id' => (int)$id], 'নোটিফিকেশন প্রকাশিত হয়েছে', 201);

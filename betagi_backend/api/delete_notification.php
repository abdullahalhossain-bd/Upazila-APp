<?php
// =====================================================
// delete_notification.php  —  Phase 6
// POST — admin only (uno OR ict OR developer)
// Required: id
// Returns {success:true, message:"..."}
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$admin = requireRole('uno', 'ict', 'developer');

$id = input('id');
if (!$id) sendError('ID দিন', 400);

$db = getDB();

$check = $db->prepare("SELECT id FROM notifications WHERE id = ?");
$check->execute([$id]);
if (!$check->fetch()) sendError('নোটিফিকেশন পাওয়া যায়নি', 404);

$db->prepare("DELETE FROM notifications WHERE id = ?")->execute([$id]);

logAdminAction($admin, 'delete', 'notification', (int)$id);

sendSuccess(null, 'নোটিফিকেশন মুছে গেছে');

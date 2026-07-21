<?php
// =====================================================
// admin_delete_budget_category.php  —  Phase 6
// POST — uno ONLY
// Required: id
// Returns {success:true, message:"..."}
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$admin = requireRole('uno');

$id = input('id');
if (!$id) sendError('ID দিন', 400);

$db = getDB();

$check = $db->prepare("SELECT id FROM budget_categories WHERE id = ?");
$check->execute([$id]);
if (!$check->fetch()) sendError('বাজেট ক্যাটাগরি পাওয়া যায়নি', 404);

$db->prepare("DELETE FROM budget_categories WHERE id = ?")->execute([$id]);

logAdminAction($admin, 'delete', 'budget_category', (int)$id);

sendSuccess(null, 'বাজেট ক্যাটাগরি মুছে গেছে');

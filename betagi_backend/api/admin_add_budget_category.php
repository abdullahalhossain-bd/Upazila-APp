<?php
// =====================================================
// admin_add_budget_category.php  —  Phase 6
// POST — uno ONLY
// Required: fiscal_year, category_name, allocated_amount, spent_amount
// Returns {success:true, message:"...", data:{id}} (201)
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$admin = requireRole('uno');

$fiscal_year      = input('fiscal_year', '2024-2025');
$category_name    = input('category_name');
$allocated_amount = input('allocated_amount', '0');
$spent_amount     = input('spent_amount', '0');

if (!$category_name) sendError('ক্যাটাগরির নাম দিন', 400);

// Coerce numeric — allow only valid decimals
$allocated = (float)$allocated_amount;
$spent     = (float)$spent_amount;
if ($allocated < 0 || $spent < 0) sendError('পরিমাণ ঋণাত্মক হতে পারবে না', 400);

$db   = getDB();
$stmt = $db->prepare("
    INSERT INTO budget_categories (fiscal_year, category_name, allocated_amount, spent_amount)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([$fiscal_year, $category_name, $allocated, $spent]);
$id   = $db->lastInsertId();

logAdminAction($admin, 'create', 'budget_category', (int)$id,
               "fy=$fiscal_year; name=$category_name; alloc=$allocated; spent=$spent");

sendSuccess(['id' => (int)$id], 'বাজেট ক্যাটাগরি যোগ হয়েছে', 201);

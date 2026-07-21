<?php
// =====================================================
// admin_update_budget_category.php  —  Phase 6
// POST — uno ONLY
// Required: id  +  any of fiscal_year, category_name, allocated_amount, spent_amount
// Returns {success:true, message:"..."}
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$admin = requireRole('uno');

$id               = input('id');
$fiscal_year      = input('fiscal_year');
$category_name    = input('category_name');
$allocated_amount = input('allocated_amount');
$spent_amount     = input('spent_amount');

if (!$id) sendError('ID দিন', 400);

// At least one update field must be present
$has_update = ($fiscal_year !== '')
           || ($category_name !== '')
           || ($allocated_amount !== '')
           || ($spent_amount !== '');
if (!$has_update) {
    sendError('আপডেট করার জন্য কমপক্ষে একটি ফিল্ড দিন', 400);
}

$db = getDB();

$check = $db->prepare("SELECT id FROM budget_categories WHERE id = ?");
$check->execute([$id]);
if (!$check->fetch()) sendError('বাজেট ক্যাটাগরি পাওয়া যায়নি', 404);

$sets   = [];
$params = [];
$changelog = [];

if ($fiscal_year !== '')      { $sets[] = 'fiscal_year = ?';      $params[] = $fiscal_year;          $changelog[] = "fy=$fiscal_year"; }
if ($category_name !== '')    { $sets[] = 'category_name = ?';    $params[] = $category_name;        $changelog[] = "name=$category_name"; }
if ($allocated_amount !== '') {
    $a = (float)$allocated_amount;
    if ($a < 0) sendError('allocated_amount ঋণাত্মক হতে পারবে না', 400);
    $sets[] = 'allocated_amount = ?'; $params[] = $a; $changelog[] = "alloc=$a";
}
if ($spent_amount !== '') {
    $s = (float)$spent_amount;
    if ($s < 0) sendError('spent_amount ঋণাত্মক হতে পারবে না', 400);
    $sets[] = 'spent_amount = ?'; $params[] = $s; $changelog[] = "spent=$s";
}
$params[] = $id;

$sql = "UPDATE budget_categories SET " . implode(', ', $sets) . " WHERE id = ?";
$db->prepare($sql)->execute($params);

logAdminAction($admin, 'update', 'budget_category', (int)$id, implode(' | ', $changelog));

sendSuccess(null, 'বাজেট ক্যাটাগরি আপডেট হয়েছে');

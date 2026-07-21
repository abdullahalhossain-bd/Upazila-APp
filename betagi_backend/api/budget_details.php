<?php
require_once 'config/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'GET') sendError('Method not allowed', 405);

$db          = getDB();
$fiscal_year = $_GET['year'] ?? '2024-2025';

$stmt = $db->prepare("SELECT * FROM budget_categories WHERE fiscal_year=? ORDER BY id ASC");
$stmt->execute([$fiscal_year]);
$categories = $stmt->fetchAll();

$total_allocated = array_sum(array_column($categories, 'allocated_amount'));
$total_spent     = array_sum(array_column($categories, 'spent_amount'));

sendSuccess([
    'fiscal_year'     => $fiscal_year,
    'total_allocated' => $total_allocated,
    'total_spent'     => $total_spent,
    'total_remaining' => $total_allocated - $total_spent,
    'categories'      => $categories,
]);

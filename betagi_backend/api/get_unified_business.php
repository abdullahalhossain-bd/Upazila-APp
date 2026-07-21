<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') sendError('Method not allowed', 405);

$db            = getDB();
$id            = $_GET['id']            ?? null;
$business_type = $_GET['business_type'] ?? '';
$union         = $_GET['union_name']    ?? '';

// Single item
if ($id) {
    $stmt = $db->prepare("SELECT * FROM unified_business_items WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) sendError('পাওয়া যায়নি', 404);
    $row['image'] = imageUrl($row['image']);
    sendSuccess($row);
}

// List — UnifiedBusinessItemActivity: new JSONArray(response) → raw []
$sql    = "SELECT * FROM unified_business_items WHERE 1=1";
$params = [];
if ($business_type) { $sql .= " AND business_type = ?"; $params[] = $business_type; }
if ($union)         { $sql .= " AND union_name = ?";    $params[] = $union; }
$sql .= " ORDER BY name ASC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

foreach ($rows as &$r) {
    // Model field: image_url
    $r['image_url'] = imageUrl($r['image']);
}

sendArray($rows);

<?php
require_once 'config/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'GET') sendError('Method not allowed', 405);

$db          = getDB();
$id          = $_GET['id']           ?? null;
$officer_type = $_GET['officer_type'] ?? '';

if ($id) {
    $stmt = $db->prepare("SELECT * FROM unified_govt_officers WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) sendError('পাওয়া যায়নি', 404);
    $row['image'] = imageUrl($row['image']);
    sendSuccess($row);
}

$sql    = "SELECT * FROM unified_govt_officers WHERE 1=1";
$params = [];
if ($officer_type) { $sql .= " AND officer_type=?"; $params[] = $officer_type; }
$sql .= " ORDER BY name ASC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
foreach ($rows as &$r) $r['image'] = imageUrl($r['image']);

sendArray($rows);

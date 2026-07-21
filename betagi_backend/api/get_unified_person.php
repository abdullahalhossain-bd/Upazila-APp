<?php
require_once 'config/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'GET') sendError('Method not allowed', 405);

$db          = getDB();
$id          = $_GET['id']          ?? null;
$person_type = $_GET['person_type'] ?? '';
$union       = $_GET['union']       ?? '';
$name        = $_GET['name']        ?? '';

if ($id) {
    $stmt = $db->prepare("SELECT * FROM unified_persons WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) sendError('পাওয়া যায়নি', 404);
    $row['image_url'] = imageUrl($row['image_url']);
    sendSuccess($row);
}

$sql    = "SELECT * FROM unified_persons WHERE 1=1";
$params = [];
if ($person_type) { $sql .= " AND person_type=?"; $params[] = $person_type; }
if ($union)       { $sql .= " AND union_name=?";  $params[] = $union; }
if ($name)        { $sql .= " AND name LIKE ?";   $params[] = "%$name%"; }
$sql .= " ORDER BY name ASC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
foreach ($rows as &$r) $r['image_url'] = imageUrl($r['image_url']);

sendArray($rows);

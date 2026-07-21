<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') sendError('Method not allowed', 405);

$db        = getDB();
$id        = $_GET['id']        ?? null;
$item_type = $_GET['item_type'] ?? '';
$union     = $_GET['union']     ?? '';

if ($id) {
    $stmt = $db->prepare("SELECT * FROM unified_govt_items WHERE id=?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if (!$item) sendError('পাওয়া যায়নি', 404);
    $item['img_url'] = imageUrl($item['img_url']);
    sendSuccess($item);
}

$sql    = "SELECT * FROM unified_govt_items WHERE 1=1";
$params = [];
if ($item_type) { $sql .= " AND item_type=?"; $params[] = $item_type; }
if ($union)     { $sql .= " AND union_name=?"; $params[] = $union; }
$sql .= " ORDER BY name ASC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();
foreach ($items as &$i) $i['img_url'] = imageUrl($i['img_url']);

sendArray($items);

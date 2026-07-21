<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') sendError('Method not allowed', 405);

$db   = getDB();
$id   = $_GET['id']   ?? null;
$name = $_GET['name'] ?? '';

// Single clinic
if ($id) {
    $stmt = $db->prepare("SELECT * FROM clinics WHERE id = ?");
    $stmt->execute([$id]);
    $c = $stmt->fetch();
    if (!$c) sendError('ক্লিনিক পাওয়া যায়নি', 404);
    $c['img']      = imageUrl($c['img']);
    $c['services'] = $c['services'] ? json_decode($c['services'], true) : [];
    sendSuccess($c);
}

// List — ClinicActivity: new JSONArray(response) → raw []
$sql    = "SELECT * FROM clinics WHERE 1=1";
$params = [];
if ($name) { $sql .= " AND name LIKE ?"; $params[] = "%$name%"; }
$sql .= " ORDER BY name ASC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$clinics = $stmt->fetchAll();

foreach ($clinics as &$c) {
    $c['img']      = imageUrl($c['img']);
    $c['services'] = $c['services'] ? json_decode($c['services'], true) : [];
    // ClinicActivity uses obj.optString("union") — map union_name → union
    $c['union']    = $c['union_name'] ?? '';
}

sendArray($clinics);

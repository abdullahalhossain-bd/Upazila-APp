<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') sendError('Method not allowed', 405);

$db          = getDB();
$id          = $_GET['id']          ?? null;
$name        = $_GET['name']        ?? '';
$blood_group = $_GET['blood_group'] ?? '';

// Single donor
if ($id) {
    $stmt = $db->prepare("SELECT * FROM blood_donors WHERE id = ?");
    $stmt->execute([$id]);
    $d = $stmt->fetch();
    if (!$d) sendError('রক্তদাতা পাওয়া যায়নি', 404);
    $d['image'] = imageUrl($d['image']);
    sendSuccess($d);
}

// List — BloodDonationActivity uses JsonArrayRequest → raw []
$sql    = "SELECT * FROM blood_donors WHERE is_active = 1";
$params = [];
if ($name)        { $sql .= " AND name LIKE ?";       $params[] = "%$name%"; }
if ($blood_group) { $sql .= " AND blood_group = ?";   $params[] = $blood_group; }
$sql .= " ORDER BY name ASC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$donors = $stmt->fetchAll();

foreach ($donors as &$d) {
    $d['image'] = imageUrl($d['image']);
}

sendArray($donors);

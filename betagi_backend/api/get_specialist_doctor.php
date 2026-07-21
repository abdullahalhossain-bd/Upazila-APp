<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') sendError('Method not allowed', 405);

$db            = getDB();
$id            = $_GET['id']            ?? null;
$doctor_type   = $_GET['doctor_type']   ?? '';
$union_name    = $_GET['union_name']    ?? '';

if ($id) {
    $stmt = $db->prepare("SELECT * FROM specialist_doctors WHERE id=?");
    $stmt->execute([$id]);
    $doc = $stmt->fetch();
    if (!$doc) sendError('ডাক্তার পাওয়া যায়নি', 404);
    $doc['image'] = imageUrl($doc['image']);
    sendSuccess($doc);
}

$sql    = "SELECT * FROM specialist_doctors WHERE 1=1";
$params = [];

if ($doctor_type) {
    $sql .= " AND doctor_type = ?";
    $params[] = $doctor_type;
}
if ($union_name) {
    $sql .= " AND union_name = ?";
    $params[] = $union_name;
}
$sql .= " ORDER BY name ASC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$docs = $stmt->fetchAll();

foreach ($docs as &$d) $d['image'] = imageUrl($d['image']);

sendArray($docs);

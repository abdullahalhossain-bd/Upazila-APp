<?php
// doctors.php → alias used in Config.java: DOCTOR_LIST_URL = BASE_URL + "doctors.php"
require_once 'config/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'GET') sendError('Method not allowed', 405);

$db          = getDB();
$doctor_type = $_GET['doctor_type'] ?? '';

$sql    = "SELECT * FROM specialist_doctors WHERE 1=1";
$params = [];
if ($doctor_type) { $sql .= " AND doctor_type=?"; $params[] = $doctor_type; }
$sql .= " ORDER BY name ASC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$docs = $stmt->fetchAll();
foreach ($docs as &$d) $d['image'] = imageUrl($d['image']);

sendArray($docs);

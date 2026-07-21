<?php
require_once 'config/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);
$auth = authUser();
$id   = input('id') ?: ($_GET['id'] ?? null);
if (!$id) sendError('ID দিন');
$db = getDB();
$stmt = $db->prepare("SELECT * FROM unified_govt_officers WHERE id=?");
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row) sendError('পাওয়া যায়নি', 404);

requireOwnerOrAdmin($auth, $row);

$db->prepare("DELETE FROM unified_govt_officers WHERE id=?")->execute([$id]);
sendSuccess(null, 'অফিসার মুছে গেছে');

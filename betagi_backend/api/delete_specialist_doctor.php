<?php
require_once 'config/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);
$auth = authUser();
$id   = input('id') ?: ($_GET['id'] ?? null);
if (!$id) sendError('ID দিন');
$db = getDB();
$stmt = $db->prepare("SELECT * FROM specialist_doctors WHERE id=?");
$stmt->execute([$id]);
$d = $stmt->fetch();
if (!$d) sendError('পাওয়া যায়নি', 404);

requireOwnerOrAdmin($auth, $d);

$db->prepare("DELETE FROM specialist_doctors WHERE id=?")->execute([$id]);
sendSuccess(null, 'ডাক্তার মুছে গেছে');

<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);
$auth = authUser();
$id   = input('id') ?: ($_GET['id'] ?? null);
if (!$id) sendError('ID দিন');

$db = getDB();
$stmt = $db->prepare("SELECT * FROM clinics WHERE id=?");
$stmt->execute([$id]);
$c = $stmt->fetch();
if (!$c) sendError('পাওয়া যায়নি', 404);

requireOwnerOrAdmin($auth, $c);

$db->prepare("DELETE FROM clinics WHERE id=?")->execute([$id]);
sendSuccess(null, 'ক্লিনিক মুছে গেছে');

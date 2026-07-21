<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$auth = authUser();
$id   = input('id') ?: ($_GET['id'] ?? null);
if (!$id) sendError('ID দিন');

$db = getDB();
$stmt = $db->prepare("SELECT * FROM notices WHERE id=?");
$stmt->execute([$id]);
$notice = $stmt->fetch();
if (!$notice) sendError('পাওয়া যায়নি', 404);

requireOwnerOrAdmin($auth, $notice);

$db->prepare("DELETE FROM notices WHERE id=?")->execute([$id]);
$stmt->execute([$id]);

sendSuccess(null, 'নোটিস মুছে গেছে');

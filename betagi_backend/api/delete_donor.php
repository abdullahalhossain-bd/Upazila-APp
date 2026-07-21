<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$auth = authUser();
$id   = input('id') ?: ($_GET['id'] ?? null);
if (!$id) sendError('ID দিন');

$db = getDB();
$stmt = $db->prepare("SELECT * FROM blood_donors WHERE id=?");
$stmt->execute([$id]);
$donor = $stmt->fetch();
if (!$donor) sendError('পাওয়া যায়নি', 404);

requireOwnerOrAdmin($auth, $donor);

$db->prepare("DELETE FROM blood_donors WHERE id=?")->execute([$id]);
$stmt->execute([$id]);

sendSuccess(null, 'রক্তদাতা মুছে গেছে');

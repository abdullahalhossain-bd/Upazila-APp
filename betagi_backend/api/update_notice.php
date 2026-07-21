<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$auth = authUser();

$id          = input('id');
$title       = input('title');
$description = input('description');
$type        = input('type');
$department  = input('department');
$notice_date = input('notice_date');
$imageB64    = $_POST['image'] ?? '';

if (!$id) sendError('Notice ID দিন');

$db   = getDB();
$stmt = $db->prepare("SELECT * FROM notices WHERE id=?");
$stmt->execute([$id]);
$notice = $stmt->fetch();
if (!$notice) sendError('নোটিস পাওয়া যায়নি', 404);


requireOwnerOrAdmin($auth, $notice);
$image = $notice['image'];
if ($imageB64) $image = saveBase64Image($imageB64, 'notices');

$stmt = $db->prepare("UPDATE notices SET title=?, type=?, department=?, description=?, image=?, notice_date=? WHERE id=?");
$stmt->execute([$title ?: $notice['title'], $type ?: $notice['type'],
                $department ?: $notice['department'], $description ?: $notice['description'],
                $image, $notice_date ?: $notice['notice_date'], $id]);

sendSuccess(['id' => $id], 'নোটিস আপডেট হয়েছে');

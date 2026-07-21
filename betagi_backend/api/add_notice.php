<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$auth = authUser();

$title       = input('title');
$description = input('description');
$type        = input('type', 'সাধারণ');
$department  = input('department');
$notice_date = input('notice_date');
$imageB64    = $_POST['image']    ?? '';
$documentB64 = $_POST['document'] ?? '';

if (!$title || !$description) sendError('শিরোনাম এবং বিবরণ দিন');

$db    = getDB();
$image = $imageB64 ? saveBase64Image($imageB64, 'notices') : null;

$stmt = $db->prepare("INSERT INTO notices (title, type, department, description, image, notice_date, created_by)
                      VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$title, $type, $department, $description, $image,
                $notice_date ?: null, $auth['id']]);
$notice_id = $db->lastInsertId();

// Save document attachment
if ($documentB64) {
    $docPath = saveBase64Image($documentB64, 'attachments');
    $stmt2 = $db->prepare("INSERT INTO notice_attachments (notice_id, file_name, file_path, file_type)
                           VALUES (?, ?, ?, 'pdf')");
    $stmt2->execute([$notice_id, 'document_' . $notice_id . '.pdf', $docPath]);
}

sendSuccess(['id' => $notice_id], 'নোটিস যোগ হয়েছে', 201);

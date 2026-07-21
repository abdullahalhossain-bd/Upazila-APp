<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$auth = authUser();

$name        = input('name');
$blood_group = input('blood_group');
$address     = input('address');
$phone       = input('phone');
$imageB64    = $_POST['image'] ?? '';

if (!$name || !$blood_group || !$phone) sendError('নাম, রক্তের গ্রুপ এবং ফোন দিন');

$db    = getDB();
$image = $imageB64 ? saveBase64Image($imageB64, 'donors') : 'default.png';

$stmt = $db->prepare("INSERT INTO blood_donors (name, blood_group, address, phone, image, user_id)
                      VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([$name, $blood_group, $address, $phone, $image, $auth['id']]);

sendSuccess(['id' => $db->lastInsertId()], 'রক্তদাতা নিবন্ধন হয়েছে', 201);

<?php
require_once 'config/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);
$auth = authUser();

$name        = input('name');
$phone       = input('phone');
$address     = input('address');
$union       = input('union');
$person_type = input('person_type');
$imageB64    = $_POST['image'] ?? '';

if (!$name) sendError('নাম দিন');

$db    = getDB();
$image = $imageB64 ? saveBase64Image($imageB64, 'people') : 'default.png';

$db->prepare("INSERT INTO unified_persons (name, phone, address, union_name, image_url, person_type, user_id)
              VALUES (?,?,?,?,?,?,?)")
   ->execute([$name, $phone, $address, $union, $image, $person_type, $auth['id']]);

sendSuccess(['id' => $db->lastInsertId()], 'যোগ হয়েছে', 201);

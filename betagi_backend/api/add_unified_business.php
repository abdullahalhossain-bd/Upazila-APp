<?php
require_once 'config/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);
$auth = authUser();

$name            = input('name');
$proprietor_name = input('proprietor_name');
$phone           = input('phone');
$address         = input('address');
$union           = input('union_name');
$business_type   = input('business_type');
$details         = input('details');
$imageB64        = $_POST['image'] ?? '';

if (!$name) sendError('দোকানের নাম দিন');

$db    = getDB();
$image = $imageB64 ? saveBase64Image($imageB64, 'business') : 'default.png';

$db->prepare("INSERT INTO unified_business_items
    (user_id, name, proprietor_name, phone, address, union_name, business_type, details, image)
    VALUES (?,?,?,?,?,?,?,?,?)")
   ->execute([$auth['id'], $name, $proprietor_name, $phone, $address, $union, $business_type, $details, $image]);

sendSuccess(['id' => $db->lastInsertId()], 'ব্যবসা যোগ হয়েছে', 201);

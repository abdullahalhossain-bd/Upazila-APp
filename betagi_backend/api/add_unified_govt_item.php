<?php
require_once 'config/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);
$auth = authUser();

$name      = input('name');
$address   = input('address');
$phone     = input('phone_number');
$item_type = input('item_type');
$union     = input('union_name');
$imageB64  = $_POST['img_url'] ?? $_POST['image'] ?? '';

if (!$name) sendError('নাম দিন');

$db    = getDB();
$image = $imageB64 ? saveBase64Image($imageB64, 'govt_items') : 'default.png';

$db->prepare("INSERT INTO unified_govt_items (name, address, phone_number, item_type, img_url, union_name, created_by)
              VALUES (?,?,?,?,?,?,?)")
   ->execute([$name, $address, $phone, $item_type, $image, $union, $auth['id']]);

sendSuccess(['id' => $db->lastInsertId()], 'যোগ হয়েছে', 201);

<?php
require_once 'config/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);
$auth = authUser();

$name         = input('name');
$designation  = input('designation');
$phone        = input('phone_number');
$officer_type = input('officer_type');
$imageB64     = $_POST['image'] ?? '';

if (!$name) sendError('নাম দিন');

$db    = getDB();
$image = $imageB64 ? saveBase64Image($imageB64, 'govt_officers') : 'default.png';

$db->prepare("INSERT INTO unified_govt_officers (name, designation, phone_number, officer_type, image, user_id)
              VALUES (?,?,?,?,?,?)")
   ->execute([$name, $designation, $phone, $officer_type, $image, $auth['id']]);

sendSuccess(['id' => $db->lastInsertId()], 'অফিসার যোগ হয়েছে', 201);

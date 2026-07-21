<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$auth = authUser();

$name                   = input('name');
$address                = input('address');
$place_name             = input('place_name');
$phone_number           = input('phone_number');
$complaint_phone_number = input('complaint_phone_number');
$email                  = input('email');
$establish_date         = input('establish_date');
$founder_name           = input('founder_name');
$transport_info         = input('transport_info');
$operating_hours        = input('operating_hours');
$union_name             = input('union_name');
$servicesJson           = input('services', '[]');   // JSON array from app
$imageB64               = $_POST['img'] ?? '';

if (!$name || !$address) sendError('ক্লিনিকের নাম ও ঠিকানা দিন');

// Validate services JSON
$services = json_decode($servicesJson, true);
if (!is_array($services)) $services = [];

$db    = getDB();
$image = $imageB64 ? saveBase64Image($imageB64, 'clinics') : 'default.png';

$stmt = $db->prepare("INSERT INTO clinics
    (name, address, place_name, phone_number, complaint_phone_number, email,
     establish_date, founder_name, transport_info, operating_hours,
     union_name, img, services, created_by)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

$stmt->execute([
    $name, $address, $place_name, $phone_number, $complaint_phone_number,
    $email, $establish_date, $founder_name, $transport_info, $operating_hours,
    $union_name, $image, json_encode($services, JSON_UNESCAPED_UNICODE),
    $auth['id']
]);

sendSuccess(['id' => $db->lastInsertId()], 'ক্লিনিক যোগ হয়েছে', 201);

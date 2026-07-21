<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$auth = authUser();

$name           = input('name');
$qualifications = input('qualifications');
$specialization = input('specialization');
$doctor_type    = input('doctor_type');
$workplace      = input('workplace');
$chamber        = input('chamber');
$visiting_hours = input('visiting_hours');
$phone          = input('phone');
$address        = input('address');
$union          = input('union');
$imageB64       = $_POST['image'] ?? '';

if (!$name || !$specialization) sendError('ডাক্তারের নাম ও বিশেষজ্ঞ ক্ষেত্র দিন');

$db    = getDB();
$image = $imageB64 ? saveBase64Image($imageB64, 'doctors') : 'default.png';

$stmt = $db->prepare("INSERT INTO specialist_doctors
    (name, qualifications, specialization, doctor_type, workplace, chamber,
     visiting_hours, phone, address, union_name, image, user_id)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");

$stmt->execute([
    $name, $qualifications, $specialization, $doctor_type, $workplace,
    $chamber, $visiting_hours, $phone, $address, $union, $image, $auth['id']
]);

sendSuccess(['id' => $db->lastInsertId()], 'ডাক্তার নিবন্ধন হয়েছে', 201);

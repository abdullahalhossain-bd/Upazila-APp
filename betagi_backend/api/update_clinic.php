<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$auth = authUser();
$id   = input('id');
if (!$id) sendError('ID দিন');

$db   = getDB();
$stmt = $db->prepare("SELECT * FROM clinics WHERE id=?");
$stmt->execute([$id]);
$c = $stmt->fetch();
if (!$c) sendError('ক্লিনিক পাওয়া যায়নি', 404);


requireOwnerOrAdmin($auth, $c);
$name                   = input('name',                   $c['name']);
$address                = input('address',                $c['address']);
$place_name             = input('place_name',             $c['place_name']);
$phone_number           = input('phone_number',           $c['phone_number']);
$complaint_phone_number = input('complaint_phone_number', $c['complaint_phone_number']);
$email                  = input('email',                  $c['email']);
$establish_date         = input('establish_date',         $c['establish_date']);
$founder_name           = input('founder_name',           $c['founder_name']);
$transport_info         = input('transport_info',         $c['transport_info']);
$operating_hours        = input('operating_hours',        $c['operating_hours']);
$union_name             = input('union_name',             $c['union_name']);
$servicesJson           = input('services',               $c['services'] ?? '[]');
$imageB64               = $_POST['img'] ?? '';

$image    = $c['img'];
if ($imageB64) $image = saveBase64Image($imageB64, 'clinics');

$services = json_decode($servicesJson, true);
if (!is_array($services)) $services = [];

$stmt = $db->prepare("UPDATE clinics SET
    name=?, address=?, place_name=?, phone_number=?, complaint_phone_number=?,
    email=?, establish_date=?, founder_name=?, transport_info=?, operating_hours=?,
    union_name=?, img=?, services=?
    WHERE id=?");

$stmt->execute([
    $name, $address, $place_name, $phone_number, $complaint_phone_number,
    $email, $establish_date, $founder_name, $transport_info, $operating_hours,
    $union_name, $image, json_encode($services, JSON_UNESCAPED_UNICODE), $id
]);

sendSuccess(['id' => $id], 'ক্লিনিক আপডেট হয়েছে');

<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$auth = authUser();
$id   = input('id');
if (!$id) sendError('ID দিন');

$db   = getDB();
$stmt = $db->prepare("SELECT * FROM blood_donors WHERE id=?");
$stmt->execute([$id]);
$donor = $stmt->fetch();
if (!$donor) sendError('রক্তদাতা পাওয়া যায়নি', 404);


requireOwnerOrAdmin($auth, $donor);
$name        = input('name',        $donor['name']);
$blood_group = input('blood_group', $donor['blood_group']);
$address     = input('address',     $donor['address']);
$phone       = input('phone',       $donor['phone']);
$imageB64    = $_POST['image'] ?? '';

$image = $donor['image'];
if ($imageB64) $image = saveBase64Image($imageB64, 'donors');

$stmt = $db->prepare("UPDATE blood_donors SET name=?, blood_group=?, address=?, phone=?, image=? WHERE id=?");
$stmt->execute([$name, $blood_group, $address, $phone, $image, $id]);

sendSuccess(['id' => $id], 'রক্তদাতার তথ্য আপডেট হয়েছে');

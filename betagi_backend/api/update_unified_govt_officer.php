<?php
require_once 'config/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);
$auth = authUser();
$id   = input('id');
if (!$id) sendError('ID দিন');

$db   = getDB();
$stmt = $db->prepare("SELECT * FROM unified_govt_officers WHERE id=?");
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row) sendError('পাওয়া যায়নি', 404);


requireOwnerOrAdmin($auth, $row);
$name         = input('name',         $row['name']);
$designation  = input('designation',  $row['designation']);
$phone        = input('phone_number', $row['phone_number']);
$officer_type = input('officer_type', $row['officer_type']);
$imageB64     = $_POST['image'] ?? '';

$image = $row['image'];
if ($imageB64) $image = saveBase64Image($imageB64, 'govt_officers');

$db->prepare("UPDATE unified_govt_officers SET name=?, designation=?, phone_number=?, officer_type=?, image=? WHERE id=?")
   ->execute([$name, $designation, $phone, $officer_type, $image, $id]);

sendSuccess(['id' => $id], 'অফিসার আপডেট হয়েছে');

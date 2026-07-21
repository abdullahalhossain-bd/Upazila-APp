<?php
require_once 'config/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);
$auth = authUser();
$id   = input('id');
if (!$id) sendError('ID দিন');

$db   = getDB();
$stmt = $db->prepare("SELECT * FROM unified_business_items WHERE id=?");
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row) sendError('পাওয়া যায়নি', 404);


requireOwnerOrAdmin($auth, $row);
$name            = input('name',            $row['name']);
$proprietor_name = input('proprietor_name', $row['proprietor_name']);
$phone           = input('phone',           $row['phone']);
$address         = input('address',         $row['address']);
$union           = input('union_name',      $row['union_name']);
$business_type   = input('business_type',   $row['business_type']);
$details         = input('details',         $row['details']);
$imageB64        = $_POST['image'] ?? '';

$image = $row['image'];
if ($imageB64) $image = saveBase64Image($imageB64, 'business');

$db->prepare("UPDATE unified_business_items SET
    name=?, proprietor_name=?, phone=?, address=?, union_name=?, business_type=?, details=?, image=?
    WHERE id=?")
   ->execute([$name, $proprietor_name, $phone, $address, $union, $business_type, $details, $image, $id]);

sendSuccess(['id' => $id], 'ব্যবসা আপডেট হয়েছে');

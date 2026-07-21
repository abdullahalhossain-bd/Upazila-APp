<?php
require_once 'config/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);
$auth = authUser();
$id   = input('id');
if (!$id) sendError('ID দিন');

$db   = getDB();
$stmt = $db->prepare("SELECT * FROM unified_persons WHERE id=?");
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row) sendError('পাওয়া যায়নি', 404);


requireOwnerOrAdmin($auth, $row);
$name        = input('name',        $row['name']);
$phone       = input('phone',       $row['phone']);
$address     = input('address',     $row['address']);
$union       = input('union',       $row['union_name']);
$person_type = input('person_type', $row['person_type']);
$imageB64    = $_POST['image'] ?? '';

$image = $row['image_url'];
if ($imageB64) $image = saveBase64Image($imageB64, 'people');

$db->prepare("UPDATE unified_persons SET name=?, phone=?, address=?, union_name=?, image_url=?, person_type=? WHERE id=?")
   ->execute([$name, $phone, $address, $union, $image, $person_type, $id]);

sendSuccess(['id' => $id], 'আপডেট হয়েছে');

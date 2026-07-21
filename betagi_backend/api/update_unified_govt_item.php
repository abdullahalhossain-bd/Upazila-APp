<?php
require_once 'config/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);
$auth = authUser();
$id   = input('id');
if (!$id) sendError('ID দিন');

$db   = getDB();
$stmt = $db->prepare("SELECT * FROM unified_govt_items WHERE id=?");
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row) sendError('পাওয়া যায়নি', 404);


requireOwnerOrAdmin($auth, $row);
$name      = input('name',         $row['name']);
$address   = input('address',      $row['address']);
$phone     = input('phone_number', $row['phone_number']);
$item_type = input('item_type',    $row['item_type']);
$union     = input('union_name',   $row['union_name']);
$imageB64  = $_POST['img_url'] ?? $_POST['image'] ?? '';

$image = $row['img_url'];
if ($imageB64) $image = saveBase64Image($imageB64, 'govt_items');

$db->prepare("UPDATE unified_govt_items SET name=?, address=?, phone_number=?, item_type=?, img_url=?, union_name=? WHERE id=?")
   ->execute([$name, $address, $phone, $item_type, $image, $union, $id]);

sendSuccess(['id' => $id], 'আপডেট হয়েছে');

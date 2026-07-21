<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$auth = authUser();
$id   = input('id');
if (!$id) sendError('ID দিন');

$db   = getDB();
$stmt = $db->prepare("SELECT * FROM specialist_doctors WHERE id=?");
$stmt->execute([$id]);
$d = $stmt->fetch();
if (!$d) sendError('ডাক্তার পাওয়া যায়নি', 404);


requireOwnerOrAdmin($auth, $d);
$name           = input('name',           $d['name']);
$qualifications = input('qualifications', $d['qualifications']);
$specialization = input('specialization', $d['specialization']);
$doctor_type    = input('doctor_type',    $d['doctor_type']);
$workplace      = input('workplace',      $d['workplace']);
$chamber        = input('chamber',        $d['chamber']);
$visiting_hours = input('visiting_hours', $d['visiting_hours']);
$phone          = input('phone',          $d['phone']);
$address        = input('address',        $d['address']);
$union          = input('union',          $d['union_name']);
$imageB64       = $_POST['image'] ?? '';

$image = $d['image'];
if ($imageB64) $image = saveBase64Image($imageB64, 'doctors');

$stmt = $db->prepare("UPDATE specialist_doctors SET
    name=?, qualifications=?, specialization=?, doctor_type=?, workplace=?,
    chamber=?, visiting_hours=?, phone=?, address=?, union_name=?, image=?
    WHERE id=?");

$stmt->execute([
    $name, $qualifications, $specialization, $doctor_type, $workplace,
    $chamber, $visiting_hours, $phone, $address, $union, $image, $id
]);

sendSuccess(['id' => $id], 'ডাক্তারের তথ্য আপডেট হয়েছে');

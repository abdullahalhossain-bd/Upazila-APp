<?php
// =====================================================
// update_emergency_number.php  —  Phase 6
// POST — admin only (uno OR ict)
// Required: id  +  at least one of (name, phone, type)
// Returns {success:true, message:"..."}
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$admin = requireRole('uno', 'ict');

$id    = input('id');
$name  = input('name');
$phone = input('phone');
$type  = input('type');

if (!$id) sendError('ID দিন', 400);
if ($name === '' && $phone === '' && $type === '') {
    sendError('আপডেট করার জন্য কমপক্ষে একটি ফিল্ড দিন (name/phone/type)', 400);
}

$db   = getDB();

// Verify row exists
$check = $db->prepare("SELECT id FROM emergency_numbers WHERE id = ?");
$check->execute([$id]);
if (!$check->fetch()) sendError('জরুরি নম্বর পাওয়া যায়নি', 404);

// Build dynamic SET clause with positional params (safe — column names are hardcoded, not user input)
$sets   = [];
$params = [];
if ($name  !== '') { $sets[] = 'name = ?';  $params[] = $name;  }
if ($phone !== '') { $sets[] = 'phone = ?'; $params[] = $phone; }
if ($type  !== '') { $sets[] = 'type = ?';  $params[] = $type;  }
$params[] = $id;

$sql = "UPDATE emergency_numbers SET " . implode(', ', $sets) . " WHERE id = ?";
$db->prepare($sql)->execute($params);

logAdminAction($admin, 'update', 'emergency_number', (int)$id,
               implode(' | ', array_filter([$name !== '' ? "name=$name" : '',
                                            $phone !== '' ? "phone=$phone" : '',
                                            $type !== '' ? "type=$type" : ''])));

sendSuccess(null, 'জরুরি নম্বর আপডেট হয়েছে');

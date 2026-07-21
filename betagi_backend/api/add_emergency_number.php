<?php
// =====================================================
// add_emergency_number.php  —  Phase 6
// POST — admin only (uno OR ict)
// Required: name, phone, type
// Returns {success:true, message:"...", data:{id}} (201)
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$admin = requireRole('uno', 'ict');

$name  = input('name');
$phone = input('phone');
$type  = input('type');

if (!$name)  sendError('নাম দিন', 400);
if (!$phone) sendError('ফোন নম্বর দিন', 400);
if (!$type)  sendError('ধরন দিন', 400);

$db   = getDB();
$stmt = $db->prepare("INSERT INTO emergency_numbers (name, phone, type) VALUES (?, ?, ?)");
$stmt->execute([$name, $phone, $type]);
$id   = $db->lastInsertId();

logAdminAction($admin, 'create', 'emergency_number', (int)$id, "$name / $phone / $type");

sendSuccess(['id' => (int)$id], 'জরুরি নম্বর যোগ হয়েছে', 201);

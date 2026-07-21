<?php
// =====================================================
// submit_complaint.php  —  Phase 6 (SECURITY FIXED)
// POST — citizens submit a complaint.
// Requires JWT (authUser) to identify the user.
// CRITICAL FIX: user_id is ALWAYS taken from JWT, never from client input.
// Required: type, title, details, location, department, priority,
//           contact_number, email, complainant_name
// Returns {success:true, message:"...", data:{id}} (201)
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$auth = authUser();   // citizen must be logged in

// CRITICAL FIX: Always use the authenticated user's ID — ignore any client-provided user_id
$user_id = (string)($auth['id'] ?? '');
if (!$user_id) sendError('Authentication required', 401);

$type             = input('type');
$title            = input('title');
$details          = input('details');
$location         = input('location');
$department       = input('department');
$priority         = input('priority', 'medium');
$contact_number   = input('contact_number');
$email            = input('email');
$complainant_name = input('complainant_name');
$imageB64         = $_POST['image'] ?? '';             // image always multipart
$complaint_date   = input('complaint_date');

if (!$user_id)           sendError('User ID দিন');
if (!$type || !$title)   sendError('অভিযোগের ধরন ও শিরোনাম দিন');
if (!$details)           sendError('অভিযোগের বিস্তারিত দিন');
if (!$contact_number)    sendError('যোগাযোগ নম্বর দিন');
if (!$complainant_name)  sendError('অভিযোগকারীর নাম দিন');

// Validate priority against enum-ish allowlist
$allowed_priority = ['low','medium','high','urgent'];
if (!in_array($priority, $allowed_priority, true)) $priority = 'medium';

// Save image (if provided)
$image_path = $imageB64 ? saveBase64Image($imageB64, 'complaints') : null;

$db  = getDB();
$stmt = $db->prepare("
    INSERT INTO complaints
      (user_id, type, title, details, location, department, priority,
       contact_number, email, complainant_name, complaint_date, image_url, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
");
$stmt->execute([
    $user_id, $type, $title, $details, $location, $department, $priority,
    $contact_number, $email, $complainant_name,
    $complaint_date ?: date('Y-m-d'),
    $image_path,
]);

$id = $db->lastInsertId();

sendSuccess(['id' => (int)$id], 'অভিযোগ সফলভাবে জমা হয়েছে', 201);

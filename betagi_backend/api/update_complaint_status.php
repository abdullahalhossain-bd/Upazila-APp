<?php
// =====================================================
// update_complaint_status.php  —  Phase 6
// POST — admin updates complaint status (+ optional reply)
// Requires role: uno OR ict  (NOT developer)
// Required: complaint_id, new_status (pending|in_progress|resolved|rejected)
// Optional: admin_note (reply text — stored in complaint_replies if non-empty)
// Returns {success:true, message:"..."}
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$admin = requireRole('uno', 'ict');

$complaint_id = input('complaint_id');
$new_status   = input('new_status');
$admin_note   = input('admin_note');   // optional reply text

if (!$complaint_id)              sendError('Complaint ID দিন', 400);
if (!$new_status)                sendError('নতুন স্ট্যাটাস দিন', 400);

$allowed_status = ['pending', 'in_progress', 'resolved', 'rejected'];
if (!in_array($new_status, $allowed_status, true)) {
    sendError('স্ট্যাটাস অবৈধ — pending|in_progress|resolved|rejected', 400);
}

$db = getDB();

// Verify complaint exists
$check = $db->prepare("SELECT id, status FROM complaints WHERE id = ?");
$check->execute([$complaint_id]);
$complaint = $check->fetch();
if (!$complaint) sendError('অভিযোগ পাওয়া যায়নি', 404);

// Update complaints.status + admin_note (keep both fields in sync — admin_note mirrors latest reply)
$db->prepare("UPDATE complaints SET status = ?, admin_note = ? WHERE id = ?")
   ->execute([$new_status, $admin_note ?: null, $complaint_id]);

// Insert into complaint_replies if a note was supplied
if ($admin_note !== '') {
    $db->prepare("INSERT INTO complaint_replies (complaint_id, admin_id, reply_text, new_status)
                  VALUES (?, ?, ?, ?)")
       ->execute([$complaint_id, $admin['id'], $admin_note, $new_status]);
}

// Best-effort audit log
logAdminAction($admin, 'update', 'complaint', (int)$complaint_id,
               "status→$new_status" . ($admin_note ? "; note=" . mb_substr($admin_note, 0, 200) : ''));

sendSuccess(null, 'অভিযোগের স্ট্যাটাস আপডেট হয়েছে');

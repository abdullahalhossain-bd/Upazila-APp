<?php
// =====================================================
// delete_emergency_number.php  —  Phase 6
// POST — admin only (uno OR ict)
// Required: id
// Returns {success:true, message:"..."}
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$admin = requireRole('uno', 'ict');

$id = input('id');
if (!$id) sendError('ID দিন', 400);

$db = getDB();

// Verify existence first so we can 404 sensibly
$check = $db->prepare("SELECT id FROM emergency_numbers WHERE id = ?");
$check->execute([$id]);
if (!$check->fetch()) sendError('জরুরি নম্বর পাওয়া যায়নি', 404);

$db->prepare("DELETE FROM emergency_numbers WHERE id = ?")->execute([$id]);

logAdminAction($admin, 'delete', 'emergency_number', (int)$id);

sendSuccess(null, 'জরুরি নম্বর মুছে গেছে');

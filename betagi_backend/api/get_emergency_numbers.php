<?php
// =====================================================
// get_emergency_numbers.php  —  Phase 6
// GET — PUBLIC (no auth)
// Returns raw JSON array of all emergency_numbers, newest first
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') sendError('Method not allowed', 405);

$db   = getDB();
$stmt = $db->query("SELECT * FROM emergency_numbers ORDER BY id ASC");
$rows = $stmt->fetchAll();

// RAW array — JsonArrayRequest expects []
sendArray($rows);

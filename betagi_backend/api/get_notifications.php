<?php
// =====================================================
// get_notifications.php  —  Phase 6
// GET — PUBLIC (no auth)
// Optional ?limit= (default 50, capped at 200)
// Returns raw JSON array of notifications, newest first
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') sendError('Method not allowed', 405);

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
if ($limit < 1)   $limit = 50;
if ($limit > 200) $limit = 200;

$db   = getDB();
$stmt = $db->prepare("SELECT n.*, u.name AS sent_by_name
                      FROM notifications n
                      LEFT JOIN users u ON n.sent_by = u.id
                      ORDER BY n.created_at DESC
                      LIMIT ?");
$stmt->execute([$limit]);
$rows = $stmt->fetchAll();

// RAW array — JsonArrayRequest expects []
sendArray($rows);

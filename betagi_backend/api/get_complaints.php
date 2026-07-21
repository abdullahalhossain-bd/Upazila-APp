<?php
// =====================================================
// get_complaints.php  —  Phase 6
// GET — list all complaints joined with complainant user info
// Optional filters: ?status=, ?type=, ?priority=
// Requires admin auth (any role).
// Returns raw JSON array (for JsonArrayRequest compatibility).
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') sendError('Method not allowed', 405);

authAdmin();

$db     = getDB();
$status   = isset($_GET['status'])   ? trim($_GET['status'])   : '';
$type     = isset($_GET['type'])     ? trim($_GET['type'])     : '';
$priority = isset($_GET['priority']) ? trim($_GET['priority']) : '';

$sql    = "SELECT c.*, u.name AS user_name, u.phone AS user_phone, u.union_name AS user_union
           FROM complaints c
           LEFT JOIN users u ON c.user_id = u.id
           WHERE 1=1";
$params = [];

if ($status !== '')   { $sql .= " AND c.status   = ?"; $params[] = $status; }
if ($type !== '')     { $sql .= " AND c.type     = ?"; $params[] = $type; }
if ($priority !== '') { $sql .= " AND c.priority = ?"; $params[] = $priority; }

$sql .= " ORDER BY c.created_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

foreach ($rows as &$c) {
    $c['image_url'] = $c['image_url'] ? imageUrl($c['image_url']) : '';
}

// RAW array — JsonArrayRequest expects []
sendArray($rows);

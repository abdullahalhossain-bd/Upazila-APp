<?php
// =====================================================
// admin_list_users.php  —  Phase 6
// GET — admin only (uno OR developer)
// Optional ?search= (matches name OR phone), ?user_type=
// Returns raw JSON array of users (without password field)
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') sendError('Method not allowed', 405);

requireRole('uno', 'developer');

$db     = getDB();
$search   = isset($_GET['search'])   ? trim($_GET['search'])   : '';
$userType = isset($_GET['user_type']) ? trim($_GET['user_type']) : '';

$sql = "SELECT id, name, phone, address, union_name, image, user_type, is_active, created_at
        FROM users
        WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND (name LIKE ? OR phone LIKE ?)";
    $like = '%' . $search . '%';
    $params[] = $like;
    $params[] = $like;
}
if ($userType !== '') {
    $sql .= " AND user_type = ?";
    $params[] = $userType;
}
$sql .= " ORDER BY created_at DESC, id DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

foreach ($rows as &$u) {
    $u['image_url'] = imageUrl($u['image'] ?? 'default.png');
    $u['image']     = basename($u['image'] ?? 'default.png');   // filename only — app prepends Config.IMAGE_URL
}

// RAW array — JsonArrayRequest expects []
sendArray($rows);

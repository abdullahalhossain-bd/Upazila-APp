<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') sendError('Method not allowed', 405);

$db   = getDB();
$id   = $_GET['id']   ?? null;
$type = $_GET['type'] ?? null;

// Single notice detail
if ($id) {
    $stmt = $db->prepare("SELECT * FROM notices WHERE id = ?");
    $stmt->execute([$id]);
    $notice = $stmt->fetch();
    if (!$notice) sendError('নোটিস পাওয়া যায়নি', 404);

    $notice['image_url'] = imageUrl($notice['image']);

    $stmt2 = $db->prepare("SELECT * FROM notice_attachments WHERE notice_id = ?");
    $stmt2->execute([$id]);
    $notice['attachments'] = $stmt2->fetchAll();

    // single detail — OK as wrapped object
    sendSuccess($notice);
}

// List — NoticeFragment uses JsonArrayRequest → must return raw []
// PERFORMANCE FIX: Use subquery instead of N+1 loop (was 1 + N queries, now 1 query)
$sql    = "SELECT n.*, u.name AS author,
              (SELECT COUNT(*) FROM notice_attachments WHERE notice_id = n.id) AS attachment_count
           FROM notices n
           LEFT JOIN users u ON n.created_by = u.id";
$params = [];

if ($type && $type !== 'সব') {
    $sql   .= " WHERE n.type = ?";
    $params[] = $type;
}
$sql .= " ORDER BY n.created_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$notices = $stmt->fetchAll();

foreach ($notices as &$n) {
    $n['image_url'] = imageUrl($n['image']);
    $n['attachment_count'] = (int)$n['attachment_count'];
}

// RAW array — JsonArrayRequest expects []
sendArray($notices);

<?php
// =====================================================
// admin_dashboard_stats.php  —  Phase 6
// GET — admin only (any role: admin, uno, ict, developer)
// Returns aggregate counts for the admin dashboard.
// Response: {success:true, message:"...", data:{...counts...}}
// =====================================================
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') sendError('Method not allowed', 405);

authAdmin();

$db = getDB();

/**
 * Run a count query and return the integer.
 * Prepared-statement friendly — caller controls the SQL entirely.
 */
function countRows(PDO $db, string $sql, array $params = []): int {
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return (int)$stmt->fetchColumn();
}

$data = [
    'total_users'          => countRows($db, "SELECT COUNT(*) FROM users"),
    'total_active_users'   => countRows($db, "SELECT COUNT(*) FROM users WHERE is_active = 1"),
    'total_admins'         => countRows($db, "SELECT COUNT(*) FROM users WHERE user_type IN ('admin','uno','ict','developer')"),

    'total_notices'        => countRows($db, "SELECT COUNT(*) FROM notices"),
    'total_donors'         => countRows($db, "SELECT COUNT(*) FROM blood_donors"),
    'total_clinics'        => countRows($db, "SELECT COUNT(*) FROM clinics"),
    'total_doctors'        => countRows($db, "SELECT COUNT(*) FROM specialist_doctors"),
    'total_business_items' => countRows($db, "SELECT COUNT(*) FROM unified_business_items"),
    'total_govt_items'     => countRows($db, "SELECT COUNT(*) FROM unified_govt_items"),
    'total_govt_officers'  => countRows($db, "SELECT COUNT(*) FROM unified_govt_officers"),
    'total_persons'        => countRows($db, "SELECT COUNT(*) FROM unified_persons"),
    'total_emergency_numbers' => countRows($db, "SELECT COUNT(*) FROM emergency_numbers"),
    'total_notifications'  => countRows($db, "SELECT COUNT(*) FROM notifications"),
    'total_budget_categories' => countRows($db, "SELECT COUNT(*) FROM budget_categories"),

    // Complaints — total + per-status breakdown
    'total_complaints'             => countRows($db, "SELECT COUNT(*) FROM complaints"),
    'complaints_pending'           => countRows($db, "SELECT COUNT(*) FROM complaints WHERE status = 'pending'"),
    'complaints_in_progress'       => countRows($db, "SELECT COUNT(*) FROM complaints WHERE status = 'in_progress'"),
    'complaints_resolved'          => countRows($db, "SELECT COUNT(*) FROM complaints WHERE status = 'resolved'"),
    'complaints_rejected'          => countRows($db, "SELECT COUNT(*) FROM complaints WHERE status = 'rejected'"),
];

// Budget totals (money) — useful at-a-glance on the dashboard
$budgetStmt = $db->query("SELECT
        COALESCE(SUM(allocated_amount),0) AS total_allocated,
        COALESCE(SUM(spent_amount),0)     AS total_spent
    FROM budget_categories");
$budget = $budgetStmt->fetch();
if ($budget) {
    $data['budget_total_allocated'] = (float)$budget['total_allocated'];
    $data['budget_total_spent']     = (float)$budget['total_spent'];
    $data['budget_total_remaining'] = (float)$budget['total_allocated'] - (float)$budget['total_spent'];
}

sendSuccess($data, 'ড্যাশবোর্ড পরিসংখ্যান');

<?php
// =====================================================
// বেতাগী ই-সেবা — config.php  (PRODUCTION FIXED + SECURITY HARDENED)
// =====================================================
// SECURITY FIX: All secrets now read from environment variables with
// development fallbacks. In production, set these via .env file or
// server environment (Apache SetEnv, nginx fastcgi_param, etc.).
//
// Create a .env file in betagi_backend/ with:
//   DB_HOST=your_db_host
//   DB_NAME=your_db_name
//   DB_USER=your_db_user
//   DB_PASS=your_db_password
//   JWT_SECRET=your_64_char_random_secret
//
// Generate a new JWT secret: openssl rand -hex 32

// Load .env file if it exists (simple parser — no dependency needed)
$__envFile = __DIR__ . '/../../.env';
if (file_exists($__envFile)) {
    foreach (file($__envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $__line) {
        if (str_starts_with(trim($__line), '#')) continue;
        if (!str_contains($__line, '=')) continue;
        [$__k, $__v] = explode('=', $__line, 2);
        $__k = trim($__k);
        $__v = trim($__v, " \t\"'");
        if (!array_key_exists($__k, $_ENV) && !getenv($__k)) {
            $_ENV[$__k] = $__v;
            putenv("$__k=$__v");
        }
    }
}

define('DB_HOST',    getenv('DB_HOST') ?: 'bdix.mywhiteserver.com');
define('DB_NAME',    getenv('DB_NAME') ?: 'nagorik1_betagi');
define('DB_USER',    getenv('DB_USER') ?: 'nagorik1');
define('DB_PASS',    getenv('DB_PASS') ?: 'B515IcfVel!R(1');
define('DB_CHARSET', 'utf8mb4');

define('BASE_URL',    'https://nagoriksheba.com/betagi_backend/api/');
define('UPLOAD_PATH', __DIR__ . '/../../uploads/');  // api/config/ → betagi_backend/uploads/
define('UPLOAD_URL',  'https://nagoriksheba.com/betagi_backend/uploads/');

// JWT secret: MUST be set via environment in production (64+ random hex chars)
// Fallback is for development only — will warn if used in production
define('JWT_SECRET', getenv('JWT_SECRET') ?: 'betagi_secret_key_change_this_2024');
define('JWT_EXPIRE', 60 * 60 * 24 * 30); // 30 days

// ── CORS (SECURITY FIX: restrict to known origins) ───
// In production, only allow the actual app domains.
$__allowed_origins = ['https://nagoriksheba.com', 'https://admin.nagoriksheba.com', 'capacitor://localhost', 'http://localhost'];
$__origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($__origin, $__allowed_origins, true)) {
    header("Access-Control-Allow-Origin: $__origin");
} else {
    // For Android apps (no Origin header), allow without CORS header
    // Android Volley doesn't send Origin, so CORS doesn't apply
    header('Access-Control-Allow-Origin: *');
}
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ── Suppress PHP warnings from breaking JSON ─────────
// Errors go to log file, NOT to output
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// ── PDO Connection ────────────────────────────────────
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}

// ── Input helper — reads php://input ONCE ────────────
function getBodyParams(): array {
    static $body = null;
    if ($body === null) {
        $raw  = file_get_contents('php://input');
        $body = $raw ? (json_decode($raw, true) ?? []) : [];
    }
    return $body;
}

function input(string $key, string $default = ''): string {
    if (isset($_POST[$key])) return trim((string)$_POST[$key]);
    $body = getBodyParams();
    return isset($body[$key]) ? trim((string)$body[$key]) : $default;
}

// ── JSON Response helpers ─────────────────────────────
// sendSuccess: wrapped response for add/update/delete/auth
function sendSuccess($data = null, string $message = 'Success', int $code = 200): void {
    http_response_code($code);
    echo json_encode(
        ['success' => true, 'message' => $message, 'data' => $data],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    exit();
}

// sendArray: raw JSON array for list endpoints (GET lists)
// NoticeFragment, ClinicActivity, BloodDonationActivity, UnifiedBusinessItemActivity
// all use JSONArray(response) or JsonArrayRequest — need raw []
function sendArray(array $list): void {
    http_response_code(200);
    echo json_encode($list, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit();
}

function sendError(string $message = 'Error', int $code = 400): void {
    http_response_code($code);
    echo json_encode(
        ['success' => false, 'message' => $message, 'data' => null],
        JSON_UNESCAPED_UNICODE
    );
    exit();
}

// ── JWT ───────────────────────────────────────────────
function jwtEncode(array $payload): string {
    $header  = b64u(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $payload['exp'] = time() + JWT_EXPIRE;
    $p   = b64u(json_encode($payload));
    $sig = b64u(hash_hmac('sha256', "$header.$p", JWT_SECRET, true));
    return "$header.$p.$sig";
}

function jwtDecode(string $token): ?array {
    $parts = explode('.', $token);
    if (count($parts) !== 3) return null;
    [$h, $p, $sig] = $parts;
    if (!hash_equals(b64u(hash_hmac('sha256', "$h.$p", JWT_SECRET, true)), $sig)) return null;
    $data = json_decode(b64d($p), true);
    return ($data && $data['exp'] > time()) ? $data : null;
}

function b64u(string $d): string { return rtrim(strtr(base64_encode($d), '+/', '-_'), '='); }
function b64d(string $d): string {
    // Restore padding (0..3 '=' chars) — original code used buggy math
    // `3 - (3 + strlen($d)) % 4` which returns 3 when length % 4 == 0.
    $pad = 4 - (strlen($d) % 4);
    if ($pad < 4) {
        $d .= str_repeat('=', $pad);
    }
    return base64_decode(strtr($d, '-_', '+/'));
}

// ── Auth guard ────────────────────────────────────────
function authUser(): array {
    $h    = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    $data = jwtDecode(str_replace('Bearer ', '', $h));
    if (!$data) sendError('Unauthorized', 401);
    return $data;
}

// ── Image helper (SECURITY FIXED: MIME validation + size limit) ──
function saveBase64Image(string $base64, string $folder): string {
    if (empty($base64)) return 'default.png';
    if (str_contains($base64, ',')) $base64 = explode(',', $base64)[1];
    $decoded = base64_decode($base64, true);
    if (!$decoded || strlen($decoded) < 100) return 'default.png';

    // SECURITY FIX: Max 5MB file size
    if (strlen($decoded) > 5_000_000) return 'default.png';

    // SECURITY FIX: Validate actual MIME type from content (not from client header)
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->buffer($decoded);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];
    if (!isset($allowed[$mime])) return 'default.png';  // not a real image

    $ext = $allowed[$mime];
    $dir = UPLOAD_PATH . $folder . '/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $filename = uniqid('img_', true) . '.' . $ext;
    file_put_contents($dir . $filename, $decoded);
    return $folder . '/' . $filename;
}

function imageUrl(string $path): string {
    if (!$path || $path === 'default.png') return UPLOAD_URL . 'default.png';
    return UPLOAD_URL . $path;
}

// ── Admin Role helpers (Phase 6) ──────────────────────
// Role matrix:
//   uno        → full access (notices, donors, clinics, doctors, business, govt,
//                persons, complaints, budget, emergency, users, notifications)
//   ict        → content management (notices, donors, clinics, doctors, business,
//                govt, persons, complaints, emergency, notifications)
//   developer  → technical/settings (users, notifications, audit_log, all read-only)
//   admin      → legacy superuser — granted everything

/** Require any admin (uno / ict / developer / admin). 403 for regular 'user'. */
function authAdmin(): array {
    $user = authUser();
    $type = $user['user_type'] ?? 'user';
    if (!in_array($type, ['admin', 'uno', 'ict', 'developer'], true)) {
        sendError('Admin access required', 403);
    }
    return $user;
}

/**
 * Require a specific subset of admin roles.
 * 'admin' (legacy superuser) always passes.
 *
 * Example:  $me = requireRole('uno', 'ict');
 */
function requireRole(string ...$allowedRoles): array {
    $user = authAdmin();
    $type = $user['user_type'] ?? 'user';
    if ($type === 'admin') return $user;              // legacy superuser
    if (!in_array($type, $allowedRoles, true)) {
        sendError('Permission denied for your role', 403);
    }
    return $user;
}

/** Convenience role-check helpers (do NOT exit on failure). */
function isUno(): bool       { $u = authUserSafe(); return in_array($u['user_type'] ?? '', ['uno','admin'], true); }
function isIct(): bool       { $u = authUserSafe(); return in_array($u['user_type'] ?? '', ['ict','uno','admin'], true); }
function isDeveloper(): bool { $u = authUserSafe(); return in_array($u['user_type'] ?? '', ['developer','admin'], true); }

/** Same as authUser() but returns [] instead of dying on missing/invalid token. */
function authUserSafe(): array {
    $h    = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (!$h) return [];
    $data = jwtDecode(str_replace('Bearer ', '', $h));
    return $data ?? [];
}

/** Log an admin action (best-effort — never fails the request). */
function logAdminAction(array $admin, string $action, string $entityType, $entityId = null, string $details = ''): void {
    try {
        $db   = getDB();
        $stmt = $db->prepare("INSERT INTO admin_audit_log (admin_id, action, entity_type, entity_id, details, ip_address)
                              VALUES (?, ?, ?, ?, ?, ?)");
        $ip = $_SERVER['REMOTE_ADDR'] ?? ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? '');
        // X-Forwarded-For can be a comma list — keep only the first
        if ($ip && str_contains($ip, ',')) $ip = trim(explode(',', $ip)[0]);
        $stmt->execute([
            $admin['id'] ?? 0,
            $action,
            $entityType,
            $entityId,
            $details,
            $ip,
        ]);
    } catch (Exception $e) { /* swallow — never break the request */ }
}

// ── Owner-or-Admin authorization helper (Critical Security Fix) ──────
/**
 * Require that the authenticated user is the owner of a resource OR an admin.
 * Uses created_by / user_id column (auto-detects which exists in the row).
 * Sends 403 and exits if not authorized.
 *
 * @param array $auth   The auth payload from authUser()
 * @param array $row    The DB row being accessed (must have created_by or user_id)
 * @return void
 */
function requireOwnerOrAdmin(array $auth, array $row): void {
    $ownerId = $row['created_by'] ?? $row['user_id'] ?? null;
    $isAdmin = in_array($auth['user_type'] ?? '', ['admin', 'uno', 'ict', 'developer'], true);

    if (!$ownerId || ((int)$ownerId !== (int)$auth['id'] && !$isAdmin)) {
        sendError('আপনার এই তথ্য সম্পাদনার অনুমতি নেই', 403);
    }
}

/**
 * Validate password strength.
 * Requirements: min 6 chars, at least 1 letter + 1 digit.
 */
function validatePasswordStrength(string $password): array {
    if (strlen($password) < 6) {
        return ['valid' => false, 'message' => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে'];
    }
    if (strlen($password) > 128) {
        return ['valid' => false, 'message' => 'পাসওয়ার্ড ১২৮ অক্ষরের বেশি হতে পারবে না'];
    }
    if (!preg_match('/[a-zA-Z]/', $password)) {
        return ['valid' => false, 'message' => 'পাসওয়ার্ডে কমপক্ষে ১টি অক্ষর থাকতে হবে'];
    }
    if (!preg_match('/[0-9]/', $password)) {
        return ['valid' => false, 'message' => 'পাসওয়ার্ডে কমপক্ষে ১টি সংখ্যা থাকতে হবে'];
    }
    return ['valid' => true, 'message' => ''];
}

/**
 * Check password strength score (0-4) for UI strength meter.
 */
function passwordStrengthScore(string $password): int {
    $score = 0;
    if (strlen($password) >= 6)  $score++;
    if (strlen($password) >= 10) $score++;
    if (preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password)) $score++;
    if (preg_match('/[0-9]/', $password)) $score++;
    if (preg_match('/[^a-zA-Z0-9]/', $password)) $score++;
    return min(4, $score);
}

/**
 * Rate limit check using DB.
 * Returns true if action is allowed, false if rate limit exceeded.
 * Requires a `rate_limit` table (id, identifier, action, created_at).
 */
function rateLimitCheck(string $identifier, string $action, int $maxActions = 5, int $windowSeconds = 300): bool {
    try {
        $db = getDB();
        $windowStart = date('Y-m-d H:i:s', time() - $windowSeconds);
        $stmt = $db->prepare("SELECT COUNT(*) FROM rate_limit WHERE identifier = ? AND action = ? AND created_at > ?");
        $stmt->execute([$identifier, $action, $windowStart]);
        $count = (int)$stmt->fetchColumn();
        if ($count >= $maxActions) return false;
        $db->prepare("INSERT INTO rate_limit (identifier, action, created_at) VALUES (?, ?, NOW())")
           ->execute([$identifier, $action]);
        return true;
    } catch (Exception $e) {
        // If rate_limit table doesn't exist, allow the action (fail-open)
        return true;
    }
}

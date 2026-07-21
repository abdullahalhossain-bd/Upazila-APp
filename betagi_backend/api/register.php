<?php
require_once 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') sendError('Method not allowed', 405);

$name      = input('name');
$phone     = input('phone');
$password  = input('password');
$address   = input('address');
$union     = input('union');
$imageB64  = $_POST['image'] ?? '';   // image always comes as POST (multipart)

if (!$name || !$phone || !$password) sendError('নাম, ফোন এবং পাসওয়ার্ড দিন');
if (!preg_match('/^01[3-9]\d{8}$/', $phone)) sendError('বৈধ ফোন নম্বর দিন');
if (strlen($password) < 6) sendError('পাসওয়ার্ড কমপক্ষে ৬ অক্ষর হতে হবে');

$db   = getDB();
$stmt = $db->prepare("SELECT id FROM users WHERE phone = ?");
$stmt->execute([$phone]);
if ($stmt->fetch()) sendError('এই ফোন নম্বর ইতোমধ্যে নিবন্ধিত');

$hashed = password_hash($password, PASSWORD_BCRYPT);
$image  = saveBase64Image($imageB64, 'profiles');

$db->prepare("INSERT INTO users (name, phone, password, address, union_name, image, user_type)
              VALUES (?, ?, ?, ?, ?, ?, 'user')")
   ->execute([$name, $phone, $hashed, $address, $union, $image]);

$id = $db->lastInsertId();

// Issue a JWT token immediately so the new user can call authenticated endpoints
// without having to log in again. Matches login.php's behavior.
$token = jwtEncode([
    'id'        => (int)$id,
    'phone'     => $phone,
    'user_type' => 'user',
]);

// RegistrationActivity expects: json.getJSONObject("data").getJSONObject("user")
// and now also reads json.optString("token")
sendSuccess([
    'user' => [
        'id'        => (string)$id,
        'name'      => $name,
        'phone'     => $phone,
        'address'   => $address,
        'union_name'=> $union,
        'image'     => basename($image),   // filename only — app prepends Config.IMAGE_URL
        'user_type' => 'user',
    ],
    'token' => $token,
], 'নিবন্ধন সফল হয়েছে', 201);

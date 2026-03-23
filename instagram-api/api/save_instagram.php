<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

$conn = new mysqli("34.14.179.112","chandu","Boomlet@123","instagram_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$data = json_decode(file_get_contents("php://input"), true);

$username = $data["username"] ?? null;
$profile_url = $data["profile_url"] ?? null;
$followers = $data["followers"] ?? null;
$following = $data["following"] ?? null;
$posts = $data["posts"] ?? null;
$bio = $data["bio"] ?? null;
$profile_pic = $data["profile_pic"] ?? null;
$verified = $data["verified"] ?? 0;
$external_url = $data["external_url"] ?? null;


$stmt = $conn->prepare("
INSERT INTO instagram_profiles
(username,profile_url,followers,following,posts,bio,profile_pic,verified,external_url)
VALUES (?,?,?,?,?,?,?,?,?)
ON DUPLICATE KEY UPDATE
profile_url=VALUES(profile_url),
followers=VALUES(followers),
following=VALUES(following),
posts=VALUES(posts),
bio=VALUES(bio),
profile_pic=VALUES(profile_pic),
verified=VALUES(verified),
external_url=VALUES(external_url),
visited_at=CURRENT_TIMESTAMP
");

$stmt->bind_param(
"ssiiissis",
$username,
$profile_url,
$followers,
$following,
$posts,
$bio,
$profile_pic,
$verified,
$external_url
);

$stmt->execute();

echo "saved";
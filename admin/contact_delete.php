<?php
include __DIR__ . "/../includes/admin_guard.php";
include __DIR__ . "/../includes/flash.php";
include __DIR__ . "/../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  http_response_code(405);
  die("Method not allowed");
}

$id = (int)($_POST["id"] ?? 0);
if ($id <= 0) die("Invalid id.");

$stmt = $conn->prepare("DELETE FROM contacts WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

$conn->close();
flash_set("success", "Message deleted successfully.");
header("Location: contacts.php");
exit;

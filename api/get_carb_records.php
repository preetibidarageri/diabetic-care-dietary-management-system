<?php
// get_carb_records.php
session_start();
include "db.php";
$userId = $_SESSION['user']['id'];

$sql = "SELECT * FROM carb_records WHERE user_id=? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$records = $result->fetch_all(MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($records);
?>

<?php
header('Content-Type: application/json');
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $sugar_level = $_POST['sugar_level'];
    $dated = $_POST['dated'];
    $times = $_POST['times'];

    $stmt = $conn->prepare("INSERT INTO blood_sugar (user_id, sugar_level, dated, times) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $user_id, $sugar_level, $dated, $times);

    if ($stmt->execute()) {
        echo json_encode(["status"=>"success", "message"=>"Record inserted successfully"]);
    } else {
        echo json_encode(["status"=>"error", "message"=>"Insert failed"]);
    }
} else {
    echo json_encode(["status"=>"error", "message"=>"Invalid Request"]);
}
?>
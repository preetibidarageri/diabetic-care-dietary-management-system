<?php
header('Content-Type: application/json');
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $sugar_level = $_POST['sugar_level'];
    $dated = $_POST['dated'];
    $times = $_POST['times'];

    $stmt = $conn->prepare("UPDATE blood_sugar SET sugar_level=?, dated=?, times=? WHERE id=?");
    $stmt->bind_param("sssi", $sugar_level, $dated, $times, $id);

    if ($stmt->execute()) {
        echo json_encode(["status"=>"success","message"=>"Record updated"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Update failed"]);
    }
} else {
    echo json_encode(["status"=>"error","message"=>"Invalid Request"]);
}
?>
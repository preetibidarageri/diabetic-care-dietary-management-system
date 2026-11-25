<?php
header('Content-Type: application/json');
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM blood_sugar WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status"=>"success","message"=>"Record deleted"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Delete failed"]);
    }
} else {
    echo json_encode(["status"=>"error","message"=>"Invalid Request"]);
}
?>
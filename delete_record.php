<?php
session_start();
if(!isset($_SESSION['user'])){
    echo json_encode(["status"=>"error","message"=>"Unauthorized"]);
    exit;
}
include "db.php";

if(isset($_POST['id'])){
    $id = intval($_POST['id']);
    $userId = $_SESSION['user']['id'];

    $sql = "DELETE FROM carb_records WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $userId);
    if($stmt->execute()){
        echo json_encode(["status"=>"success","message"=>"Record deleted successfully"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Failed to delete"]);
    }
} else {
    echo json_encode(["status"=>"error","message"=>"Invalid request"]);
}
?>

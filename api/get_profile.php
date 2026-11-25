<?php
header('Content-Type: application/json');
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $stmt = $conn->prepare("SELECT id,name,email,mobile,gender,dob,icr,isf FROM users WHERE mobile=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode(["status"=>"success","user"=>$user]);
    } else {
        echo json_encode(["status"=>"error","message"=>"User not found"]);
    }
} else {
    echo json_encode(["status"=>"error","message"=>"Invalid Request"]);
}
?>
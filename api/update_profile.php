<?php
header('Content-Type: application/json');
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $icr = $_POST['icr'];
    $isf = $_POST['isf'];

    $stmt = $conn->prepare("UPDATE users SET name=?,  gender=?, dob=?, icr=?, isf=? WHERE mobile=?");
    $stmt->bind_param("ssssddi", $name, $mobile, $gender, $dob, $icr, $isf, $id);

    if ($stmt->execute()) {
        echo json_encode(["status"=>"success","message"=>"Profile updated successfully"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Update failed"]);
    }
} else {
    echo json_encode(["status"=>"error","message"=>"Invalid Request"]);
}
?>
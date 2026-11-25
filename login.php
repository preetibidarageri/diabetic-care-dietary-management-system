<?php
header("Content-Type: application/json");
include "db.php";
session_start();

$data = json_decode(file_get_contents("php://input"), true);

if(!$data){
    echo json_encode(["status"=>"error","message"=>"No data received"]);
    exit;
}

$mobile = $data['mobile'];
$password = $data['password'];

$sql = "SELECT * FROM users WHERE mobile=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $mobile);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    $user = $result->fetch_assoc();
    if(password_verify($password, $user['password'])){
        unset($user['password']); // remove hash before sending back
        $_SESSION['user'] = $user;
        //$_SESSION['uid'] = $user['id'];

        echo json_encode(["status"=>"success","message"=>"Login successful"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"Incorrect password"]);
    }
} else {
    echo json_encode(["status"=>"error","message"=>"User not found"]);
}
?>

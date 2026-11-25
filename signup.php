<?php
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$name = $data['name'];
$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_BCRYPT);
$mobile = $data['mobile'];
$gender = $data['gender'];
$dob = $data['dob'];
$icr_breakfast = $data['icr_breakfast'];
$icr_lunch = $data['icr_lunch'];
$icr_dinner = $data['icr_dinner'];
$isf = $data['isf'];

$sql = "INSERT INTO users 
(name,email,password,mobile,gender,dob,icr_breakfast,icr_lunch,icr_dinner,isf) 
VALUES (?,?,?,?,?,?,?,?,?,?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssdddd", 
    $name, 
    $email, 
    $password, 
    $mobile, 
    $gender, 
    $dob, 
    $icr_breakfast, 
    $icr_lunch, 
    $icr_dinner,
    $isf
);


if($stmt->execute()){
    echo json_encode(["status"=>"success","message"=>"Signup successful"]);
} else {
    echo json_encode(["status"=>"error","message"=>$stmt->error]);
}
?>

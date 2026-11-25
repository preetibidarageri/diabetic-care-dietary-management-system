<?php
header("Content-Type: application/json");
session_start();
if(!isset($_SESSION['user'])){
    echo json_encode(["status"=>"error","message"=>"Unauthorized"]);
    exit;
}

include "db.php";
$data = json_decode(file_get_contents("php://input"), true);

$id     = $data['id'];
$name   = $data['name'];
$email  = $data['email'];
$mobile = $data['mobile'];
$gender = $data['gender'];
$dob    = $data['dob'];
$icr_breakfast = $data['icr_breakfast'];
$icr_lunch = $data['icr_lunch'];
$icr_dinner = $data['icr_dinner'];
$isf    = $data['isf'];

$sql = "UPDATE users SET name=?, email=?, mobile=?, gender=?, dob=?, 
        icr_breakfast=?, icr_lunch=?, icr_dinner=?, isf=? 
        WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssdddd",
    $name, $email, $mobile, $gender, $dob,
    $icr_breakfast, $icr_lunch, $icr_dinner, $isf,
    $id
);


if($stmt->execute()){
    // also update session so topbar name changes immediately
    $_SESSION['user']['name']   = $name;
    $_SESSION['user']['email']  = $email;
    $_SESSION['user']['mobile'] = $mobile;
    $_SESSION['user']['gender'] = $gender;
    $_SESSION['user']['dob']    = $dob;
$_SESSION['user']['icr_breakfast'] = $icr_breakfast;
$_SESSION['user']['icr_lunch'] = $icr_lunch;
$_SESSION['user']['icr_dinner'] = $icr_dinner;
    $_SESSION['user']['isf']    = $isf;

    echo json_encode(["status"=>"success","message"=>"Profile updated successfully"]);
} else {
    echo json_encode(["status"=>"error","message"=>"Update failed"]);
}
?>

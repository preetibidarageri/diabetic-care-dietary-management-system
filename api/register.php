<?php
header('Content-Type: application/json');
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $mobile = $_POST['mobile'];
    $gender = $_POST['gender'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $icr = $_POST['icr'] ?? null;
    $isf = $_POST['isf'] ?? null;

    // check email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already exists"]);
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name,email,password,mobile,gender,dob,icr,isf) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssdd", $name, $email, $password, $mobile, $gender, $dob, $icr, $isf);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "User registered successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Registration failed"]);
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}
?>

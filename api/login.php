<?php
header('Content-Type: application/json');
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['mno'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, mobile, password FROM users WHERE mobile=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            echo json_encode([
                "status" => "success",
                "message" => "Login successful",
                "user" => [
                    "id" => $row['id'],
                    "name" => $row['name'],
                    "mobile" => $row['mobile']
                ]
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid password"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}
?>

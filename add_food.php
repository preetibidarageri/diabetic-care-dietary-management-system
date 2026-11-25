<?php
header("Content-Type: application/json");
require "db.php"; // stops execution if DB not found

// Prevent PHP notices from corrupting JSON
error_reporting(E_ALL & ~E_NOTICE);

$data = json_decode(file_get_contents("php://input"), true);
$name = trim($data['name'] ?? '');
$carbs = floatval($data['carbs'] ?? 0);

if(!$name || $carbs <= 0){
    echo json_encode(["status"=>"error","message"=>"Invalid input"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO food_master (name, carbs_per_100g) VALUES (?, ?)");
$stmt->bind_param("sd", $name, $carbs);

if($stmt->execute()){
    echo json_encode([
        "status" => "success",
        "message" => "Food added successfully",
        "id" => $stmt->insert_id
    ]);
} else {
    // Handle duplicate entry gracefully
    if(strpos($db->error, "Duplicate") !== false){
        echo json_encode([
            "status" => "error",
            "message" => "Food already exists"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => $conn->error
        ]);
    }
}
?>

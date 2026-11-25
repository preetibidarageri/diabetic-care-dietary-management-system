<?php
header("Content-Type: application/json");
require "db.php";

$sql = "SELECT id, name, carbs_per_100g FROM food_master ORDER BY name ASC";
$result = $conn->query($sql);

if(!$result){
    echo json_encode([
        "status" => "error",
        "message" => $conn->error
    ]);
    exit;
}

$foods = [];
while($row = $result->fetch_assoc()){
    $foods[] = $row;
}

echo json_encode($foods);
?>

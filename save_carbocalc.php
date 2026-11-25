<?php
session_start();
include "db.php";
$userId = $_SESSION['user']['id'];

$data = json_decode(file_get_contents("php://input"), true);

$meal = $data['meal_type'];
$food_type = $data['food_type'];
$total_weight = $data['total_weight'];
$total_carb = $data['total_carb'];
$insulin_a = $data['insulin_a'];
$insulin_b = $data['insulin_b'];
$blood_sugar = $data['blood_sugar'];
$total_insulin = $data['total_insulin'];

$conn->begin_transaction();
try {
    $stmt = $conn->prepare("INSERT INTO carb_records (user_id, meal_type, food_type, total_weight, total_carb, insulin_a, insulin_b, blood_sugar, total_insulin) VALUES (?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("issdddddd", $userId, $meal, $food_type, $total_weight, $total_carb, $insulin_a, $insulin_b, $blood_sugar, $total_insulin);
    $stmt->execute();
    $record_id = $stmt->insert_id;

    foreach($data['items'] as $item){
        $food_id = $item['food_id'] ?: NULL;
        $name = $item['name'];
        $weight = $item['weight'];
        $carbs = $item['carbs'];
        $stmt2 = $conn->prepare("INSERT INTO carb_record_items (record_id, food_id, item_name, weight, carbs) VALUES (?,?,?,?,?)");
        $stmt2->bind_param("iisdd", $record_id, $food_id, $name, $weight, $carbs);
        $stmt2->execute();
    }

    $conn->commit();
    echo json_encode(["status"=>"success","message"=>"Record saved"]);
} catch (Exception $e){
    $conn->rollback();
    echo json_encode(["status"=>"error","message"=>"Error saving record"]);
}
?>

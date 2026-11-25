<?php
include "db.php";
$data = json_decode(file_get_contents("php://input"), true);

$user_id = $data['user_id'] ?? 1;
$meal_type = $data['meal_type'];
$food_type = $data['food_type'];
$total_weight = $data['total_weight'];
$total_carb = $data['total_carb'];
$insulin_a = $data['insulin_a'];
$insulin_b = $data['insulin_b'];
$blood_sugar = $data['blood_sugar'];
$total_insulin = $data['total_insulin'];
$items = $data['items'];

$stmt = $conn->prepare("INSERT INTO carb_records (user_id, meal_type, food_type, total_weight, total_carb, insulin_a, insulin_b, blood_sugar, total_insulin) VALUES (?,?,?,?,?,?,?,?,?)");
$stmt->bind_param("issdddddd",$user_id,$meal_type,$food_type,$total_weight,$total_carb,$insulin_a,$insulin_b,$blood_sugar,$total_insulin);
$stmt->execute();
$record_id = $stmt->insert_id;

foreach($items as $it){
    // insert food if not exist
    $food_id = $it['food_id'];
    if(!$food_id){
        $stmt2=$conn->prepare("INSERT INTO food_master(name, carbs_per_100g) VALUES (?,?)");
        $stmt2->bind_param("sd",$it['item_name'],$it['carbs']);
        $stmt2->execute();
        $food_id = $stmt2->insert_id;
    }
    $stmt3=$conn->prepare("INSERT INTO carb_record_items(record_id, food_id, item_name, weight, carbs) VALUES (?,?,?,?,?)");
    $stmt3->bind_param("iissd",$record_id,$food_id,$it['item_name'],$it['weight'],$it['carbs']);
    $stmt3->execute();
}

echo json_encode(["status"=>"success","record_id"=>$record_id]);
?>
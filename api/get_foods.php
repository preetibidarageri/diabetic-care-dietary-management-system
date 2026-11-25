<?php
//get_foods.php
include "db.php";

$result = $conn->query("SELECT * FROM food_master");
$foods = [];
while($row = $result->fetch_assoc()){
    $foods[] = $row;
}
echo json_encode($foods);
?>
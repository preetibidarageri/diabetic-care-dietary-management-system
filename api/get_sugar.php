<?php
header('Content-Type: application/json');
include "db.php";

$user_id = $_GET['user_id'];

$result = $conn->query("SELECT * FROM blood_sugar WHERE user_id='$user_id' ORDER BY dated DESC, times DESC");

$data = [];
while($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(["status"=>"success","data"=>$data]);
?>
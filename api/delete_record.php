<?php
// delete_records.php
include "db.php";

$id = $_POST['id'] ?? 0;
if(!$id){ echo json_encode(["status"=>"error","message"=>"Invalid ID"]); exit; }

$conn->query("DELETE FROM carb_record_items WHERE record_id=$id");
$conn->query("DELETE FROM carb_records WHERE id=$id");

echo json_encode(["status"=>"success","message"=>"Record deleted"]);
?>

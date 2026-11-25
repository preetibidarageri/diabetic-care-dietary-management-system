<?php
include "db.php";

$user_id = $_POST['user_id'] ?? 0;
$filter_date = $_POST['filter_date'] ?? '';

$sql = "SELECT times, sugar_level FROM blood_sugar 
        WHERE user_id = '$user_id' AND dated = '$filter_date' 
        ORDER BY times ASC";

$result = mysqli_query($conn, $sql);
$data = [];
while($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
echo json_encode($data);
?>

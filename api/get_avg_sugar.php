<?php
include "db.php";

$user_id = $_POST['user_id'] ?? 0;

$sql = "SELECT dated, AVG(sugar_level) as avg_level 
        FROM blood_sugar 
        WHERE user_id = '$user_id' 
        GROUP BY dated 
        ORDER BY dated ASC";

$result = mysqli_query($conn, $sql);
$data = [];
while($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
echo json_encode($data);
?>

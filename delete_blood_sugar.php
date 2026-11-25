<?php
include 'db.php';
$id = $_GET['id'];
$sql = "DELETE FROM blood_sugar WHERE id='$id'";
mysqli_query($conn, $sql);
header("Location: bloodsugar.php");
?>
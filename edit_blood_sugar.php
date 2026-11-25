<?php
include 'db.php';
$id = $_POST['id'];
$dated = $_POST['dated'];
$times = $_POST['times'];
$level = $_POST['sugar_level'];

$sql = "UPDATE blood_sugar SET sugar_level='$level', dated='$dated', times='$times' WHERE id='$id'";
mysqli_query($conn, $sql);
header("Location: bloodsugar.php");
?>
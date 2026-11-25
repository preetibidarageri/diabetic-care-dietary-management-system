<?php
session_start();
include 'db.php';

$userId = $_SESSION['user']['id'];
$dated = $_POST['dated'];
$times = $_POST['times'];
$level = $_POST['sugar_level'];

$sql = "INSERT INTO blood_sugar (user_id, sugar_level, dated, times) VALUES ('$userId', '$level', '$dated', '$times')";
mysqli_query($conn, $sql);
header("Location: bloodsugar.php");
?>
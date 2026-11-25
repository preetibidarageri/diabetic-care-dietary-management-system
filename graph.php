<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: index.html"); // redirect if not logged in
    exit;
}

include "db.php";
$userId = $_SESSION['user']['id'];

 // ALL DATES CHART DATA
$avgQuery = "SELECT dated, AVG(sugar_level) as avg_level 
             FROM blood_sugar 
             WHERE user_id = '$userId' 
             GROUP BY dated 
             ORDER BY dated ASC";

$avgResult = mysqli_query($conn, $avgQuery);
$dates = [];
$avgLevels = [];
while($row = mysqli_fetch_assoc($avgResult)) {
    $dates[] = $row['dated'];
    $avgLevels[] = round($row['avg_level'], 2);
}

// DATE-WISE CHART DATA
$dateFilter = $_GET['filter_date'] ?? '';
$timeLabels = [];
$sugarValues = [];

if ($dateFilter) {
    $timeQuery = "SELECT times, sugar_level FROM blood_sugar 
                  WHERE user_id = '$userId' AND dated = '$dateFilter' 
                  ORDER BY times ASC";

    $timeResult = mysqli_query($conn, $timeQuery);
    while($row = mysqli_fetch_assoc($timeResult)) {
        $timeLabels[] = $row['times'];
        $sugarValues[] = $row['sugar_level'];
    }
}

// RANGE DATE AVG GRAPH
$rangeFrom = $_GET['range_from'] ?? '';
$rangeTo   = $_GET['range_to'] ?? '';

$rangeDates = [];
$rangeAvg = [];

if ($rangeFrom && $rangeTo) {
    $rangeQuery = "SELECT dated, AVG(sugar_level) as avg_level
                   FROM blood_sugar
                   WHERE user_id = '$userId'
                   AND dated BETWEEN '$rangeFrom' AND '$rangeTo'
                   GROUP BY dated
                   ORDER BY dated ASC";

    $rangeResult = mysqli_query($conn, $rangeQuery);

    while ($row = mysqli_fetch_assoc($rangeResult)) {
        $rangeDates[] = $row['dated'];
        $rangeAvg[] = round($row['avg_level'], 2);
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="css/dashboard.css">
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap JS Bundle (with Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .card {
    max-width: 700px;
    width: 100%;
    border-radius: 20px;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin: 20px auto;
    display: flex;
    flex-direction: column;
    }

    .card {
    max-width: 700px;
    width: 100%;
    border-radius: 20px;
    background-color: #fff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    padding: 30px;
    margin: 40px auto;
}
.card h2 {
    color: #333;
    font-weight: bold;
}
.btn-primary {
    background: #6a11cb;
    border: none;
}
.btn-primary:hover {
    background: #2575fc;
}

canvas {
    background: #fff;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Diabetics</h2>
    <ul>
        <li><a href="diabetesinfo.php">Info</a></li>
        <li><a href="home.php">Home</a></li>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="carbocalc.php">Carbo Calc</a></li>
        <li><a href="carbinfo.php">Carb Info</a></li>
        <li><a href="bloodsugar.php">Blood Sugar</a></li>
        <li><a href="daibeticsplan.php">Daibetics Plan</a></li>
        <li><a href="graph.php">Graph</a>
        <li><a href="logout.php" id="logout">Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main">
    <div class="topbar p-3 bg-light shadow-sm">
        <h3 class="m-0">Welcome, <span id="username">Application</span></h3>
    </div>
 
      

<h4 class="mt-5">📅 All Dates Graph (Average per Day)</h4>
<canvas id="allDatesChart" height="100"></canvas>


<h4 class="mt-5">📍 Date-Wise Blood Sugar</h4>
<form method="GET" class="mb-3">
    <label>Select Date:</label>
    <input type="date" name="filter_date" value="<?= $_GET['filter_date'] ?? '' ?>" required>
    <button class="btn btn-sm btn-primary">Show Graph</button>
</form>
<canvas id="dateWiseChart" height="100"></canvas>

<h4 class="mt-5">📊 Date-Range Average Blood Sugar</h4>

<form method="GET" class="mb-3">
    <label>From:</label>
    <input type="date" name="range_from" value="<?= $_GET['range_from'] ?? '' ?>" required>

    <label class="ms-2">To:</label>
    <input type="date" name="range_to" value="<?= $_GET['range_to'] ?? '' ?>" required>

    <button class="btn btn-sm btn-success ms-2">Show Range Graph</button>
</form>

<canvas id="rangeChart" height="100"></canvas>



</div>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
 
<script src="js/dashboard.js"></script>
<script>
const allDatesLabels = <?= json_encode($dates) ?>;
const allDatesData = <?= json_encode($avgLevels) ?>;

const timeLabels = <?= json_encode($timeLabels) ?>;
const sugarValues = <?= json_encode($sugarValues) ?>;
</script>
<script>
const allDatesChart = new Chart(document.getElementById("allDatesChart"), {
    type: "line",
    data: {
        labels: allDatesLabels,
        datasets: [{
            label: "Avg Blood Sugar (mg/dL)",
            data: allDatesData,
            borderColor: "#6a11cb",
            backgroundColor: "rgba(106, 17, 203, 0.2)",
            fill: true,
            tension: 0.4,
            pointRadius: 5,
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: false }
        }
    }
});

if (timeLabels.length > 0) {
    const dateWiseChart = new Chart(document.getElementById("dateWiseChart"), {
        type: "bar",
        data: {
            labels: timeLabels,
            datasets: [{
                label: "Blood Sugar (mg/dL)",
                data: sugarValues,
                backgroundColor: "#2575fc",
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: false }
            }
        }
    });
}
</script>
<script>
const rangeDates = <?= json_encode($rangeDates) ?>;
const rangeAvgValues = <?= json_encode($rangeAvg) ?>;
</script>
<script>
if (rangeDates.length > 0) {
    new Chart(document.getElementById("rangeChart"), {
        type: "line",
        data: {
            labels: rangeDates,
            datasets: [{
                label: "Avg Sugar (mg/dL)",
                data: rangeAvgValues,
                borderColor: "#ff5722",
                backgroundColor: "rgba(255, 87, 34, 0.3)",
                fill: true,
                tension: 0.4,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: false }
            }
        }
    });
}
</script>

 

</body>
</html>

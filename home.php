<?php
session_start();
include 'db.php';

// User ID
$user_id = $_SESSION['user']['id'];

$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($user_query);

// ------------------------
// VIEW TYPE SELECTION
// ------------------------
$view_type = $_GET['view_type'] ?? 'all';
$from = $_GET['from'] ?? null;
$to = $_GET['to'] ?? null;

// ------------------------
// BUILD QUERY BASED ON SELECTION
// ------------------------
if ($view_type === "today") {
    $today = date("Y-m-d");
    $filter_sql = "SELECT * FROM blood_sugar WHERE user_id='$user_id' AND dated='$today'";
} elseif ($view_type === "range" && $from && $to) {
    $filter_sql = "SELECT * FROM blood_sugar WHERE user_id='$user_id' AND dated BETWEEN '$from' AND '$to'";
} else {
    $filter_sql = "SELECT * FROM blood_sugar WHERE user_id='$user_id'";
}

$result = mysqli_query($conn, $filter_sql);

// ------------------------
// CALCULATE MIN, MAX, AVG
// ------------------------
$values = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $values[] = $row['sugar_level'];
    }
}

$max_sugar = $values ? max($values) : "--";
$min_sugar = $values ? min($values) : "--";
$avg_sugar = $values ? round(array_sum($values) / count($values), 2) : "--";

// ------------------------
// HBA1C CALCULATION
// ------------------------
$hba1c_value = "--";
$hba1c_color = "black";
$daysCount = count(array_unique(array_map(function($v) { return $v['dated']; }, mysqli_fetch_all(mysqli_query($conn, $filter_sql), MYSQLI_ASSOC))));

if ($avg_sugar !== "--") {
    if ($daysCount < 7) {
        $hba1c_value = "Insufficient data (< 7 days)";
    } else {
        $hba1c_value = round(($avg_sugar + 46.7) / 28.7, 2);

        if ($hba1c_value < 5.7) {
            $hba1c_color = "green";
        } elseif ($hba1c_value < 6.5) {
            $hba1c_color = "orange";
        } else {
            $hba1c_color = "red";
        }
    }
}

// ------------------------
// CHART — ALL RECORDS
// ------------------------
$sql_chart = "SELECT sugar_level, dated, times FROM blood_sugar WHERE user_id='$user_id' ORDER BY dated,times";
$result_chart = mysqli_query($conn, $sql_chart);

$dates = [];
$levels = [];
while ($row2 = mysqli_fetch_assoc($result_chart)) {
    $dates[] = $row2['dated'] . " " . $row2['times'];
    $levels[] = $row2['sugar_level'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="css/dashboard.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.cards { display: flex; gap: 20px; margin: 20px 0; }
.card { flex: 1; background: #f5f5f5; padding: 20px; border-radius: 10px; text-align:center; box-shadow: 0 3px 6px rgba(0,0,0,0.1); }
.hba1c-value { font-size: 22px; font-weight: bold; }
.filter-box {
    border-left: 5px solid #6a11cb;
    background: #f8f9ff;
}

.filter-box label {
    color: #333;
    font-size: 14px;
}

.filter-box .form-select,
.filter-box .form-control {
    border-radius: 8px;
    padding: 8px 10px;
    border: 1px solid #ced4da;
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
        <li><a href="daibeticsplan.php">Plan</a></li>
        <li><a href="graph.php">Graph</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN -->
<div class="main">

    <h1>Welcome, <?= $user['name']; ?></h1>

    <!-- Filtering -->
<form method="GET" class="mb-3 p-3 bg-white shadow-sm rounded filter-box">

    <div class="row align-items-end">

        <!-- View Type Selector -->
        <div  class="col-md-4">
            <label style="font-size: medium;" class="form-label fw-bold">Select View:</label>
            <select name="view_type" class="form-select">
                <option value="all" <?= $view_type=='all'?'selected':'' ?>>All Records</option>
                <option value="today" <?= $view_type=='today'?'selected':'' ?>>Today</option>
                <option value="range" <?= $view_type=='range'?'selected':'' ?>>Date Range</option>
            </select>
        </div>

        <!-- Date Range -->
        <div class="col-md-3" id="rangeFrom" style="display:<?= $view_type=='range'?'block':'none' ?>;">
            <label class="form-label fw-bold">From:</label>
            <input type="date" name="from" class="form-control" value="<?= $from ?>">
        </div>

        <div class="col-md-3" id="rangeTo" style="display:<?= $view_type=='range'?'block':'none' ?>;">
            <label class="form-label fw-bold">To:</label>
            <input type="date" name="to" class="form-control" value="<?= $to ?>">
        </div>

        <!-- Apply Button -->
        <div class="col-md-2">
            <button class="btn btn-primary w-100 mt-3">Apply</button>
        </div>

    </div>

</form>


<script>
document.querySelector("select[name='view_type']").addEventListener("change", function(){
    document.getElementById("rangeInputs").style.display = this.value === 'range' ? 'inline-block' : 'none';
});
</script>

    <!-- Cards -->
    <div class="cards">
        <div class="card">
            <h3>Max Sugar</h3>
            <p><?= $max_sugar ?></p>
        </div>
        <div class="card">
            <h3>Min Sugar</h3>
            <p><?= $min_sugar ?></p>
        </div>
        <div class="card">
            <h3>Avg Sugar</h3>
            <p><?= $avg_sugar ?></p>
        </div>
        <div class="card">
            <h3>HbA1c</h3>
            <p class="hba1c-value" style="color:<?= $hba1c_color ?>;">
                <?= $hba1c_value ?>
            </p>
        </div>
    </div>
     <p>
        Type 1 diabetes is a chronic autoimmune condition in which the body’s immune system mistakenly attacks and destroys the insulin-producing beta cells in the pancreas. 
         As a result, the body is unable to produce insulin, a hormone that is essential for regulating blood sugar levels and allowing glucose to enter cells for energy. This leads to high levels of glucose in the blood, which can cause serious health problems if not managed properly. Type 1 diabetes often develops in childhood or adolescence, but it can occur at any age. Common symptoms include excessive thirst, frequent urination, unexplained weight loss, fatigue, and blurred vision. Management of type 1 diabetes requires lifelong insulin therapy, either through injections or an insulin pump, along with regular blood sugar monitoring, a healthy diet, and physical activity. Unlike type 2 diabetes, it is not caused by lifestyle factors, and currently, there is no known way to prevent it. Proper management and regular medical care help people with type 1 diabetes live healthy, active lives while reducing the risk of complications such as heart disease, kidney problems, nerve damage, and vision loss.
     </p>

     <!-- DOWNLOAD REPORT SECTION (restored exactly) -->
<div class="card p-3 mt-4">
    <h4>Download Diabetic Report</h4>

    <form method="POST" action="generate_diabetic_report.php" class="mb-3">
        <input type="hidden" name="report_type" value="today">
        <button class="btn btn-primary w-100">Download Today's Report (PDF)</button>
    </form>

    <hr>

    <form method="POST" action="generate_diabetic_report.php">
        <label>Select From Date:</label>
        <input type="date" name="from" class="form-control" required>

        <label class="mt-2">Select To Date:</label>
        <input type="date" name="to" class="form-control" required>

        <input type="hidden" name="report_type" value="range">
        <button class="btn btn-success w-100 mt-3">Download Date-Range Report (PDF)</button>
    </form>
</div>

    <!-- CHART -->
    <div class="charts">
        <h4>Blood Sugar Graph</h4>
        <canvas id="chart1"></canvas>
    </div>

</div>

<script>
const labels = <?= json_encode($dates) ?>;
const data = <?= json_encode($levels) ?>;

new Chart(document.getElementById('chart1'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Blood Sugar Level',
            data: data,
            borderColor:'blue',
            backgroundColor:'rgba(0,0,255,0.2)',
            tension:0.3,
            fill:true
        }]
    }
});
</script>
<script>
document.querySelector("select[name='view_type']").addEventListener("change", function () {
    let isRange = this.value === 'range';
    document.getElementById("rangeFrom").style.display = isRange ? 'block' : 'none';
    document.getElementById("rangeTo").style.display = isRange ? 'block' : 'none';
});
</script>

</body>
</html>

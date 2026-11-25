<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.html");
    exit;
}

include "db.php";
$userId = $_SESSION['user']['id'];

// --------- Try the table your project likely uses: food_master
// If your table name is different, change 'food_master' to the correct one.
$tableName = 'food_master';

// Build query. Adjust column names if your table uses different names.
$foodQuery = "SELECT id, name AS food_name, carbs_per_100g FROM `$tableName` ORDER BY name ASC";
$foodResult = mysqli_query($conn, $foodQuery);

// If query failed, capture error (for dev). Do NOT show DB errors on production.
$foodFetchRows = [];
if ($foodResult === false) {
    // helpful debug info — remove or comment out in production
    $dbErr = mysqli_error($conn);
    // fallback: try another likely schema (food chart)
    $tryAlt = mysqli_query($conn, "SELECT * FROM carb_food_chart LIMIT 1");
    if ($tryAlt !== false) {
        // if alt exists, use it
        $tableName = 'carb_food_chart';
        $foodQuery = "SELECT * FROM `$tableName` ORDER BY food_name ASC";
        $foodResult = mysqli_query($conn, $foodQuery);
    }
}

// If still false, we gracefully proceed with empty array and show a message on page
if ($foodResult && $foodResult !== false) {
    while ($row = mysqli_fetch_assoc($foodResult)) {
        $foodFetchRows[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Carbohydrate Info</title>

<link rel="stylesheet" href="css/dashboard.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.card { border-radius: 20px; background:#fff; box-shadow:0 4px 15px rgba(0,0,0,0.08); padding:20px; margin-bottom:20px; }
.card h4 { color:#6a11cb; font-weight:600; }
.table thead { background:#6a11cb; color:#fff; }
.sidebar a.active {  color:#fff !important; }
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
        <li><a href="carbinfo.php" class="active">Carb Info</a></li>
        <li><a href="bloodsugar.php">Blood Sugar</a></li>
        <li><a href="daibeticsplan.php">Diabetics Plan</a></li>
        <li><a href="graph.php">Graph</a></li>
        <li><a href="logout.php" id="logout">Logout</a></li>
    </ul>
</div>

<div class="main p-4">
    <h2 class="mb-4">Carbohydrate Information & Food Chart</h2>

    <!-- Calculator Card -->
    <div class="card">
        <h4>Carbohydrate Calculator (Per 100g)</h4>
        <p>Enter known weight (grams) and carbs for that weight to calculate carbs per 100g.</p>

        <div class="row g-2">
            <div class="col-md-4">
                <label class="form-label">Known Weight (g)</label>
                <input type="number" id="knownWeight" class="form-control" placeholder="e.g., 45">
            </div>
            <div class="col-md-4">
                <label class="form-label">Carbs in that weight (g)</label>
                <input type="number" id="knownCarbs" class="form-control" placeholder="e.g., 12">
            </div>
            <div class="col-md-4">
                <label class="form-label">Carbs per 100g</label>
                <input type="text" id="carb100" class="form-control" readonly placeholder="Result">
            </div>
        </div>

        <button id="calcBtn" class="btn btn-primary mt-3 w-100">Calculate</button>
    </div>

    <!-- Food Chart -->

    <title>Carbohydrate Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .category-btn {
            width: 200px;
            margin: 10px;
            font-weight: bold;
        }
        #foodTable {
            margin-top: 20px;
            display: none;
        }
    </style>
</head>
<body class="p-4">

<h2 class="text-center mb-4">Food Carbohydrate Information</h2>

<div class="d-flex flex-wrap justify-content-center">

    <button class="btn btn-primary category-btn" onclick="showTable('general')">General Items</button>
    <button class="btn btn-info category-btn" onclick="showTable('dairy')">Dairy Items</button>
    <button class="btn btn-warning category-btn" onclick="showTable('nuts')">Nuts</button>
    <button class="btn btn-success category-btn" onclick="showTable('pulses')">Pulses</button>
    <button class="btn btn-secondary category-btn" onclick="showTable('sprouts')">Sprouts</button>
    

</div>

<!-- Table -->
<div id="foodTable" class="container mt-3">
    <h4 id="tableTitle"></h4>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Item</th>
                <th>Cooked Weight (g/ml)</th>
                <th>Carb (g)</th>
            </tr>
        </thead>
        <tbody id="tableBody"></tbody>
    </table>
</div>

<script>

// ------------------ FIXED FOOD DATA ------------------
const foodData = {

    general: [
        { item: "Rice", weight: "100g", carb: "28g" },
        { item: "Chapati", weight: "1 pc (40g)", carb: "18g" }
        // ➕ If you add new items here → table increases
    ],

    dairy: [
        { item: "Milk", weight: "100ml", carb: "5g" },
        { item: "Curd", weight: "100g", carb: "4g" }
    ],

    nuts: [
        { item: "Almond (Badam)", weight: "10g", carb: "2g" },
        { item: "Cashew (Kaju)", weight: "10g", carb: "3g" }
    ],

    pulses: [
        { item: "Toor Dal", weight: "100g", carb: "20g" },
        { item: "Moong Dal", weight: "100g", carb: "19g" }
    ],

    sprouts: [
        { item: "Moong Sprouts", weight: "1 cup (80g)", carb: "12g" },
        { item: "Chana Sprouts", weight: "1 cup (80g)", carb: "13g" }
    ]
};

// ----------- FUNCTION TO SHOW TABLE BASED ON CATEGORY -----------
function showTable(category) {

    document.getElementById("foodTable").style.display = "block";

    // Set table title
    document.getElementById("tableTitle").innerText =
        category.charAt(0).toUpperCase() + category.slice(1) + " Items";

    // Fill table
    let tbody = document.getElementById("tableBody");
    tbody.innerHTML = "";

    foodData[category].forEach(row => {
        tbody.innerHTML += `
            <tr>
                <td>${row.item}</td>
                <td>${row.weight}</td>
                <td>${row.carb}</td>
            </tr>
        `;
    });
}

</script>

<script>
// Calculator logic (robust)
document.getElementById('calcBtn').addEventListener('click', function () {
    const w = parseFloat(document.getElementById('knownWeight').value);
    const c = parseFloat(document.getElementById('knownCarbs').value);

    if (!w || w <= 0 || isNaN(w) || isNaN(c)) {
        alert('Please enter valid numbers for weight and carbs.');
        return;
    }
    const result = (c / w) * 100;
    document.getElementById('carb100').value = result.toFixed(2) + ' g';
});



</script>

</body>
</html>

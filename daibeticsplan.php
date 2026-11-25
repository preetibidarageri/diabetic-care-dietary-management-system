<?php
session_start();
include ('db.php');

// $conn = new mysqli($host, $user, $pass, $dbname);

 
//$user_id = 2; // example user
$user_id = $_SESSION['user']['id'];
// 1. Latest Blood Sugar
$sql = "SELECT sugar_level, dated, times 
        FROM blood_sugar 
        WHERE user_id = '$user_id' 
        ORDER BY dated DESC, times DESC LIMIT 1";
$latestSugar = $conn->query($sql)->fetch_assoc();

// 2. Average Carb & Insulin
$sql = "SELECT AVG(total_carb) AS avg_carb, AVG(total_insulin) AS avg_insulin 
        FROM carb_records WHERE user_id = '$user_id'";
$avgData = $conn->query($sql)->fetch_assoc();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Diabetic Meal Plan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background: #f4f6f9;
      font-family: "Segoe UI", sans-serif;
    }
    .meal-card {
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }
    .meal-card:hover {
      transform: scale(1.02);
    }
    .meal-title {
      font-size: 1.25rem;
      font-weight: bold;
      color: #0d6efd;
    }
    .meal-carb {
      font-weight: bold;
      color: #198754;
    }
    .header-section {
      background: linear-gradient(90deg, #0d6efd, #0dcaf0);
      color: white;
      padding: 20px;
      border-radius: 12px;
      margin-bottom: 20px;
    }
  </style>
</head>
<body class="container py-4">

  <!-- Header -->
  <div class="header-section text-center">
    <h2>🍽 Personalized Diabetic Meal Plan</h2>
    <p>User ID: <?php echo $user_id; ?> | Latest Sugar: 
<b>
<?php 
if ($latestSugar) {
    echo $latestSugar['sugar_level'] . " mg/dl (" . $latestSugar['dated'] . " " . $latestSugar['times'] . ")";
} else {
    echo "No Records Found";
}
?>
</b>
</p>

    <?php 
$avgCarb = $avgData['avg_carb'] ?? 0;
$avgIns = $avgData['avg_insulin'] ?? 0;
?>
<p>
Average Carbs/Meal: <b><?php echo round($avgCarb,2); ?> g</b> |
Average Insulin: <b><?php echo round($avgIns,2); ?> units</b>
</p>

  </div>

  <!-- Meal Plan Cards -->
  <div class="row g-4">

    <!-- Breakfast -->
    <div class="col-md-6">
      <div class="card meal-card p-3">
        <div class="card-body">
          <div class="meal-title">🥞 Breakfast</div>
          <p>2 Chapatis + Vegetable Curry + 1 Boiled Egg</p>
          <p class="meal-carb">Approx Carbs: 50g</p>
        </div>
      </div>
    </div>

    <!-- Snack -->
    <div class="col-md-6">
      <div class="card meal-card p-3">
        <div class="card-body">
          <div class="meal-title">🍎 Mid-Morning Snack</div>
          <p>1 Apple + Green Tea</p>
          <p class="meal-carb">Approx Carbs: 15g</p>
        </div>
      </div>
    </div>

    <!-- Lunch -->
    <div class="col-md-6">
      <div class="card meal-card p-3">
        <div class="card-body">
          <div class="meal-title">🍛 Lunch</div>
          <p>1 Chapati + 100g Rice + Mixed Vegetables + Dal</p>
          <p class="meal-carb">Approx Carbs: 60g</p>
        </div>
      </div>
    </div>

    <!-- Evening Snack -->
    <div class="col-md-6">
      <div class="card meal-card p-3">
        <div class="card-body">
          <div class="meal-title">☕ Evening Snack</div>
          <p>Roasted Chana + Herbal Tea</p>
          <p class="meal-carb">Approx Carbs: 10g</p>
        </div>
      </div>
    </div>

    <!-- Dinner -->
    <div class="col-md-12">
      <div class="card meal-card p-3">
        <div class="card-body">
          <div class="meal-title">🍲 Dinner</div>
          <p>2 Chapatis + Paneer/Chicken + Salad</p>
          <p class="meal-carb">Approx Carbs: 60g</p>
        </div>
      </div>
    </div>

  </div>

  <!-- Notes -->
  <div class="alert alert-info mt-4">
    <b>💡 Tips:</b> Adjust insulin based on carb intake. Prefer low-GI foods (oats, brown rice, quinoa). 
    Avoid heavy rice meals at night. Drink plenty of water and check sugar 2h after main meals.
  </div>

</body>
</html>

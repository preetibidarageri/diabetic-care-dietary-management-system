<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: index.html"); // redirect if not logged in
    exit;
}
include "db.php";
$user_id = $_SESSION['user']['id'];

$user_name_q = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$res_user_name = mysqli_fetch_assoc($user_name_q);
$icr_breakfast = $res_user_name['icr_breakfast'];
$icr_lunch = $res_user_name['icr_lunch'];
$icr_dinner = $res_user_name['icr_dinner'];

$isf = $res_user_name['isf'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carbohydrate Calculator</title>
<link rel="stylesheet" href="css/dashboard.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
body { background:#f9f9f9; }
.card {
    max-width: 850px;
    width: 100%;
    border-radius: 20px;
    background-color: #fff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    padding: 30px;
    margin: 30px auto;
}
.card h2 { color:#333; font-weight:600; }
.btn-primary { background:#6a11cb; border:none; }
.btn-primary:hover { background:#2575fc; }
.sidebar { position:fixed; top:0; left:0; width:200px; height:100%; background:#6a11cb; color:#fff; padding:20px; }
.sidebar h2 { color:#fff; margin-bottom:20px; }
.sidebar ul { list-style:none; padding:0; }
.sidebar ul li { margin-bottom:15px; }
.sidebar ul li a { color:#fff; text-decoration:none; }
.main { margin-left:220px; padding:20px; }
.item-row { background:#f8f9fa; border-radius:10px; padding:10px; }
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
        <li><a href="daibeticsplan.php">Diabetics Plan</a></li>
        <li><a href="graph.php">Graph</a></li>
        <li><a href="logout.php" id="logout">Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main">
    <div class="topbar p-3 bg-light shadow-sm mb-4">
        <h3 class="m-0">Carbohydrate Calculator</h3>
    </div>

    <div class="card">
        <h2 class="text-center mb-4">Meal Entry</h2>
        <form id="carbForm">

            <!-- Meal Type -->
            <div class="mb-3">
                <label class="form-label">Meal Type</label>
                <select class="form-select" name="meal_type" required>
                    <option value="">-- Select Meal --</option>
                    <option value="Breakfast">Breakfast</option>
                    <option value="Lunch">Lunch</option>
                    <option value="Dinner">Dinner</option>
                </select>
            </div>

            <!-- Food Type -->
            <div class="mb-3">
                <label class="form-label">Food Type</label>
                <select class="form-select" name="food_type" required>
                    <option value="">-- Select Type --</option>
                    <option value="Raw">Raw Food</option>
                    <option value="Cooked">Cooked Food</option>
                </select>
            </div>

            <!-- Food Name -->
            <div class="mb-3">
                <label class="form-label">Meal Name</label>
                <input type="text" class="form-control" name="food_name" placeholder="Eg. Breakfast Combo" required>
            </div>

            <!-- Dynamic Items -->
            <h5 class="mt-4">Food Items</h5>
            <div id="itemsList"></div>
            <button type="button" class="btn btn-primary mt-2" id="addItemBtn">+ Add Food Item</button>

            <hr>

            <!-- Totals -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Total Weight (g)</label>
                    <input type="number" step="0.1" id="totalWeight" class="form-control" name="total_weight" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Total Carbohydrate (g)</label>
                    <input type="number" step="0.1" id="totalCarb" class="form-control" name="total_carb" readonly>
                </div>
            </div>

            <!-- Insulin Inputs -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Insulin A</label>
                    <input type="number" step="0.1" class="form-control" id="insulinA" name="insulin_a" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Blood Sugar Before Food</label>
                    <input type="number" step="0.1" class="form-control" id="bloodSugar" name="blood_sugar" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Insulin B</label>
                    <input type="number" step="0.1" class="form-control" id="insulinB" name="insulin_b" readonly>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Total Insulin Dose (A + B)</label>
                <input type="number" step="0.1" class="form-control" id="totalInsulin" name="total_insulin" readonly>
            </div>

            <button type="submit" class="btn btn-primary w-100">💾 Save Record</button>
        </form>
    </div>
</div>

<script>
    
let foods = [];

// Load master foods
async function loadFoods(){
    const res = await fetch("get_foods.php");
    foods = await res.json();
}
loadFoods();

// Update totals
function updateTotals(){
    let totalW=0, totalC=0;
    document.querySelectorAll(".item-row").forEach(row=>{
        let w = parseFloat(row.querySelector(".weight").value)||0;
        let c = parseFloat(row.querySelector(".carb").value)||0;
        totalW += w; totalC += c;
    });
    document.getElementById("totalWeight").value = totalW.toFixed(2);
    document.getElementById("totalCarb").value = totalC.toFixed(2);

    // 👉 Auto calculate Insulin A
    let icr_breakfast = <?php echo $icr_breakfast;?>;
let icr_lunch = <?php echo $icr_lunch;?>;
let icr_dinner = <?php echo $icr_dinner;?>;

    // Detect meal ICR dynamically
let mealType = document.querySelector("select[name='meal_type']").value;
let icr = 1;

if (mealType === "Breakfast") icr = icr_breakfast;
else if (mealType === "Lunch") icr = icr_lunch;
else if (mealType === "Dinner") icr = icr_dinner;

let insulinA = (totalC / icr).toFixed(1);
document.getElementById("insulinA").value = insulinA;


    // Update total insulin if B already exists
    let insulinB = parseFloat(document.getElementById("insulinB").value) || 0;
    document.getElementById("totalInsulin").value = (parseFloat(insulinA) + insulinB).toFixed(1);
    // Recalculate insulin A automatically when meal type changes
document.querySelector("select[name='meal_type']").addEventListener("change", updateTotals);

}

// Add new item row
document.getElementById("addItemBtn").addEventListener("click", ()=>{
    let div = document.createElement("div");
    div.className = "row mb-2 item-row";
    div.innerHTML = `
        <div class="col-md-4">
            <select class="form-select foodSelect" required>
                <option value="">-- Select Food --</option>
                ${foods.map(f=>`<option value="${f.id}" data-carb="${f.carbs_per_100g}">${f.name}</option>`).join("")}
                <option value="new">+ Add New Food</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" step="0.1" class="form-control weight" placeholder="Weight (g)" required>
        </div>
        <div class="col-md-3">
            <input type="number" step="0.1" class="form-control carb" placeholder="Carb (auto)" readonly>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm removeItem">X</button>
        </div>
    `;
    document.getElementById("itemsList").appendChild(div);

    let foodSel = div.querySelector(".foodSelect");
    let weightInp = div.querySelector(".weight");
    let carbInp = div.querySelector(".carb");

    // Auto-calc carbs
    weightInp.addEventListener("input", ()=>{
        let carb100 = parseFloat(foodSel.selectedOptions[0]?.dataset.carb)||0;
        let w = parseFloat(weightInp.value)||0;
        carbInp.value = ((carb100/100)*w).toFixed(2);
        updateTotals();
    });

    div.querySelector(".removeItem").addEventListener("click", ()=>{ div.remove(); updateTotals(); });
});

// Auto calculate Insulin B from blood sugar
document.getElementById("bloodSugar").addEventListener("input", function () {

    let bs = parseFloat(this.value) || 0;
    let target = 120; 
    let isf = <?php echo $isf; ?>;
    let insulinB = 0;

    // Always calculate difference (both high and low)
    let diff = bs - target;

    // If blood sugar is lower than 120 → diff becomes negative
    // Example: 80 - 120 = -40 → (-40) / ISF = -0.8 units
    insulinB = (diff / isf).toFixed(1);

    document.getElementById("insulinB").value = insulinB;

    // Update Total Insulin = A + B
    let insulinA = parseFloat(document.getElementById("insulinA").value) || 0;

    document.getElementById("totalInsulin").value =
        (insulinA + parseFloat(insulinB)).toFixed(1);
});


// Submit record
document.getElementById('carbForm').addEventListener('submit', async e=>{
    e.preventDefault();
    let formData = new FormData(e.target);
    let data = Object.fromEntries(formData.entries());
    data.items = [];
    document.querySelectorAll(".item-row").forEach(row=>{
        let foodSel = row.querySelector(".foodSelect");
        let weight = row.querySelector(".weight").value;
        let carb = row.querySelector(".carb").value;
        data.items.push({food_id: foodSel.value, name: foodSel.selectedOptions[0].text, weight, carbs: carb});
    });

    Swal.fire({title:"Saving...",allowOutsideClick:false,didOpen:()=>Swal.showLoading()});
    const res = await fetch("save_carbocalc.php",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify(data)});
    const result = await res.json();
    Swal.close();
    Swal.fire(result.status==="success"?"Success":"Error",result.message,result.status);
    if(result.status==="success") e.target.reset();
});

// SHOW POPUP WHEN USER SELECTS "+ Add New Food"
document.addEventListener("change", async function (e) {
    if (e.target.classList.contains("foodSelect")) {

        if (e.target.value === "new") {

            const { value: formValues } = await Swal.fire({
                title: "Add New Food",
                html: `
                    <input id="newFoodName" class="swal2-input" placeholder="Food Name">
                    <input id="newFoodCarb" type="number" class="swal2-input" placeholder="Carbs per 100g">
                `,
                confirmButtonText: "OK",
                showCancelButton: true,
                preConfirm: () => {
                    let name = document.getElementById("newFoodName").value.trim();
                    let carbs = document.getElementById("newFoodCarb").value;

                    if (!name || !carbs) {
                        Swal.showValidationMessage("Please enter both fields");
                        return false;
                    }

                    return { name, carbs };
                }
            });

            if (!formValues) {
                e.target.value = "";
                return;
            }

            // SEND DATA TO add_food.php
            let res = await fetch("add_food.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formValues)
            });

            let result = await res.json();

            if (result.status === "success") {
                Swal.fire("Success", "Food Added Successfully", "success");

                // reload fresh food list
                await loadFoods();

                // update dropdown
                let newOption = document.createElement("option");
                newOption.value = result.id;
                newOption.dataset.carb = formValues.carbs;
                newOption.textContent = formValues.name;

                e.target.appendChild(newOption);
                e.target.value = result.id; // auto select newly added item

            } else {
                Swal.fire("Error", result.message, "error");
                e.target.value = "";
            }
        }
    }
});

</script>
</body>
</html>

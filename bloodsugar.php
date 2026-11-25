<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: index.html"); // redirect if not logged in
    exit;
}

include "db.php";
$userId = $_SESSION['user']['id'];

 
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
        <h3 class="m-0">Welcome, <span id="username">Carborate Calculation</span></h3>
    </div>
<div class="card">
    <h2 class="text-center mb-4">Blood Sugar Level</h2>
   <?php
$query = "SELECT * FROM blood_sugar WHERE user_id = '$userId' ORDER BY dated DESC, times DESC";
$result = mysqli_query($conn, $query);
?><!-- Add Button -->
<div class="d-flex justify-content-end mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add Blood Sugar</button>
</div>

<!-- Data Table -->
<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Sugar Level (mg/dL)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= htmlspecialchars($row['dated']) ?></td>
            <td><?= htmlspecialchars($row['times']) ?></td>
            <td><?= htmlspecialchars($row['sugar_level']) ?></td>
            <td>
                <button class="btn btn-warning btn-sm editBtn" 
                    data-id="<?= $row['id'] ?>" 
                    data-date="<?= $row['dated'] ?>" 
                    data-time="<?= $row['times'] ?>" 
                    data-level="<?= $row['sugar_level'] ?>">
                    Edit
                </button>
                <button class="btn btn-danger btn-sm deleteBtn" data-id="<?= $row['id'] ?>">Delete</button>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</div>
    <!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="add_blood_sugar.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Blood Sugar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="date" name="dated" class="form-control mb-3" required>
        <input type="time" name="times" class="form-control mb-3" required>
        <input type="number" name="sugar_level" class="form-control mb-3" placeholder="Sugar Level" required>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
    </form>
  </div>
</div>
<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="edit_blood_sugar.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Blood Sugar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="editId">
        <input type="date" name="dated" id="editDate" class="form-control mb-3" required>
        <input type="time" name="times" id="editTime" class="form-control mb-3" required >
        <input type="number" name="sugar_level" id="editLevel" class="form-control mb-3" required>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Update</button>
      </div>
    </form>
  </div>
</div>

</div>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
 
<script src="js/dashboard.js"></script>

 <script>
document.querySelectorAll('.editBtn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        const date = btn.dataset.date;
        const time = btn.dataset.time;
        const level = btn.dataset.level;

        document.getElementById('editId').value = id;
        document.getElementById('editDate').value = date;
        document.getElementById('editTime').value = time;
        document.getElementById('editLevel').value = level;

        new bootstrap.Modal(document.getElementById('editModal')).show();
    });
});

document.querySelectorAll('.deleteBtn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `delete_blood_sugar.php?id=${id}`;
            }
        });
    });
});

document.getElementById("times").addEventListener("input", function () {
    if (!this.value) return;

    let [hour, minute] = this.value.split(":").map(Number);
    let ampm = (hour >= 12) ? "PM" : "AM";

    // Convert 24h → 12h format for display only
    let displayHour = hour % 12 || 12;

    // Show the AM/PM text next to input
    document.getElementById("ampmDisplay").innerText = `${displayHour}:${minute.toString().padStart(2, "0")} ${ampm}`;
});

</script>

</body>
</html>

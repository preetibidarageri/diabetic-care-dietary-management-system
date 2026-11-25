<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: index.html");
    exit;
}
include "db.php";
$userId = $_SESSION['user']['id'];

// Fetch records
$sql = "SELECT * FROM carb_records WHERE user_id=? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$records = $result->fetch_all(MYSQLI_ASSOC);
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

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    
/* Layout Fix */
.wrapper {
    display: flex;
    min-height: 100vh;
}

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

/* Main content */
.main {
    flex: 1;
    margin-left: 250px; /* Prevent overlap */
    padding: 20px;
    
}

/* Card & Table */
.card {
    border-radius: 20px;
    background-color: #fff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    padding: 20px;
    max-width: 1500px;
}
.table th {
    background: #6a11cb;
    color: #fff;
    text-align: center;
}
.table td {
    vertical-align: middle;
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

<div class="wrapper">
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
            <li><a href="graph.php">Graph</a></li>
            <li><a href="logout.php" id="logout">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="m-0">Carbohydrate Records</h2>
                <a href="add_carbocal.php" class="btn btn-primary">➕ Add Food</a>
            </div>

            <div class="table-responsive">
                <table id="recordsTable" class="table table-striped table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Meal</th>
                            <th>Food Type</th>
                            <!-- <th>Food Name</th> -->
                            <th>Weight (g)</th>
                            <th>Total Carb</th>
                            <th>Insulin A</th>
                            <th>Blood Sugar</th>
                            <th>Insulin B</th>
                            <th>Total Insulin</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($records as $row): ?>
                        <tr id="row-<?= $row['id'] ?>">
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['meal_type']) ?></td>
                            <td><?= htmlspecialchars($row['food_type']) ?></td>
                            <!-- <td><?= htmlspecialchars($row['food_name']) ?></td> -->
                            <td><?= $row['total_weight'] ?></td>
                            <td><?= $row['total_carb'] ?></td>
                            <td><?= $row['insulin_a'] ?></td>
                            <td><?= $row['blood_sugar'] ?></td>
                            <td><?= $row['insulin_b'] ?></td>
                            <td><?= $row['total_insulin'] ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $row['id'] ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize DataTable
$(document).ready(function () {
    $('#recordsTable').DataTable({
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50],
        ordering: true,
        searching: true
    });
});

// Delete Record with SweetAlert
$(document).on("click", ".delete-btn", function () {
    const id = $(this).data("id");
    Swal.fire({
        title: "Are you sure?",
        text: "This record will be permanently deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("delete_record.php", { id }, function (response) {
                if (response.status === "success") {
                    $("#row-" + id).fadeOut(500, function(){ $(this).remove(); });
                    Swal.fire("Deleted!", response.message, "success");
                } else {
                    Swal.fire("Error!", response.message, "error");
                }
            }, "json");
        }
    });
});
</script>
</body>
</html>

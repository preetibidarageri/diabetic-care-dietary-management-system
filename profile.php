<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: index.html"); // redirect if not logged in
    exit;
}

include "db.php";
$userId = $_SESSION['user']['id'];

// fetch user data
$sql = "SELECT id, name, email, mobile, gender, dob, icr_breakfast, icr_lunch, icr_dinner, isf FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
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
        <h3 class="m-0">Welcome, <?= $user['name']; ?><span id="username"></span></h3>
    </div>

    <div class="card">
        <h2 class="text-center mb-4">My Profile</h2>
        <form id="profileForm" class="row g-3">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">

            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Mobile</label>
                <input type="text" name="mobile" class="form-control" value="<?= htmlspecialchars($user['mobile']) ?>" readonly required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <option value="Male"   <?= $user['gender']=='Male'?'selected':'' ?>>Male</option>
                    <option value="Female" <?= $user['gender']=='Female'?'selected':'' ?>>Female</option>
                    <option value="Other"  <?= $user['gender']=='Other'?'selected':'' ?>>Other</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="dob" class="form-control" value="<?= $user['dob'] ?>">
            </div>

            <div class="col-md-4">
    <label class="form-label">ICR (Breakfast)</label>
    <input type="number" step="0.01" name="icr_breakfast" class="form-control" value="<?= $user['icr_breakfast'] ?>">
</div>

<div class="col-md-4">
    <label class="form-label">ICR (Lunch)</label>
    <input type="number" step="0.01" name="icr_lunch" class="form-control" value="<?= $user['icr_lunch'] ?>">
</div>

<div class="col-md-4">
    <label class="form-label">ICR (Dinner)</label>
    <input type="number" step="0.01" name="icr_dinner" class="form-control" value="<?= $user['icr_dinner'] ?>">
</div>


            <div class="col-md-3">
                <label class="form-label">ISF</label>
                <input type="number" step="0.01" name="isf" class="form-control" value="<?= $user['isf'] ?>">
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary w-100 py-2">Update Profile</button>
            </div>
        </form>
    </div>
</div>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById("profileForm").addEventListener("submit", async function(e){
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    Swal.fire({
        title: 'Updating...',
        text: 'Please wait while we save your changes',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    try {
        const res = await fetch("update_profile.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(data)
        });
        const result = await res.json();
        Swal.close();

        Swal.fire({
            icon: result.status === 'success' ? 'success' : 'error',
            title: result.status === 'success' ? 'Updated!' : 'Error',
            text: result.message
        }).then(()=>{
            if(result.status==='success'){
                location.reload(); // refresh to see updated values
            }
        });
    } catch(err){
        Swal.close();
        Swal.fire("Error","Server not responding","error");
    }
});
</script>
<script src="js/dashboard.js"></script>
</body>
</html>

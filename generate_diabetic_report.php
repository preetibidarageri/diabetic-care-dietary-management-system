<?php
require('fpdf/fpdf.php');
include 'db.php';
session_start();

$user_id = $_SESSION['user']['id'];  // Mobile is stored as user_id in your DB

// ------------------------------
// GET REPORT TYPE (today / range)
// ------------------------------
$report_type = $_POST['report_type'] ?? "today";
$from = $_POST['from'] ?? null;
$to   = $_POST['to'] ?? null;


// ------------------------------
// Fetch user details
// ------------------------------
$user_sql = "SELECT * FROM users WHERE id = '$user_id'";
$user_res = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_res);


// ------------------------------
// SQL for Today or Range
// ------------------------------
if ($report_type === "range" && $from && $to) {

    // ---- DATE RANGE MODE ----
    $sugar_sql = "
        SELECT * FROM blood_sugar 
        WHERE user_id = '$user_id' 
        AND dated BETWEEN '$from' AND '$to'
        ORDER BY dated, times
    ";

    $carb_sql = "
        SELECT * FROM carb_records 
        WHERE user_id = '$user_id'
        AND DATE(created_at) BETWEEN '$from' AND '$to'
        ORDER BY created_at
    ";

} else {

    // ---- TODAY MODE ----
    $today = date("Y-m-d");

    $sugar_sql = "
        SELECT * FROM blood_sugar 
        WHERE user_id = '$user_id' 
        AND dated = '$today'
        ORDER BY dated, times
    ";

    $carb_sql = "
        SELECT * FROM carb_records 
        WHERE user_id = '$user_id'
        AND DATE(created_at) = '$today'
        ORDER BY created_at
    ";
}

// ------------------------------
// Execute Queries
// ------------------------------
$sugar_res = mysqli_query($conn, $sugar_sql);
$carb_res = mysqli_query($conn, $carb_sql);


// ------------------------------
// Create PDF
// ------------------------------
$pdf = new FPDF();
$pdf->AddPage();


// ------------------------------
// Title
// ------------------------------
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Diabetic Report - '.$user['name'],0,1,'C');
$pdf->Ln(5);

// ------------------------------
// User Info
// ------------------------------
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,'Name: '.$user['name'],0,1);
$pdf->Cell(0,8,'Email: '.$user['email'],0,1);
$pdf->Cell(0,8,'DOB: '.$user['dob'],0,1);
$pdf->Cell(0,8,'ICR-Breakfast: '.$user['icr_breakfast'].' | ICR-Lunch: '.$user['icr-lunch'].'|ICR_Dinner: '.$users['icr_dinner'],0,1);
$pdf->Cell(w: 0,h: 8,txt: 'ISF: '.$user['isf'],border:0, ln:1);
if ($report_type === "today") {
    $pdf->Cell(0,8,'Report Type: Today ('.date("Y-m-d").')',0,1);
} else {
    $pdf->Cell(0,8,"Report Range: $from to $to",0,1);
}

$pdf->Ln(5);


// ------------------------------
// BLOOD SUGAR SECTION
// ------------------------------
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Blood Sugar Records',0,1);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,8,'Date',1);
$pdf->Cell(30,8,'Time',1);
$pdf->Cell(40,8,'Sugar Level',1);
$pdf->Ln();

$pdf->SetFont('Arial','',12);

// Handle no records
if (mysqli_num_rows($sugar_res) == 0) {
    $pdf->Cell(110,8,'No Records Found',1);
    $pdf->Ln();
} else {
    while($row = mysqli_fetch_assoc($sugar_res)) {
        $pdf->Cell(40,8,$row['dated'],1);
        $pdf->Cell(30,8,$row['times'],1);
        $pdf->Cell(40,8,$row['sugar_level'].' mg/dL',1);
        $pdf->Ln();
    }
}

$pdf->Ln(5);


// ------------------------------
// CARBOHYDRATE & INSULIN SECTION
// ------------------------------
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Carbohydrate & Insulin Records',0,1);

if (mysqli_num_rows($carb_res) == 0) {
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,8,'No Carb Records Found',0,1);
} else {
    while($carb = mysqli_fetch_assoc($carb_res)) {

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,8,"Meal: ".$carb['meal_type']." | Food Type: ".$carb['food_type']." | Date: ".$carb['created_at'],0,1);

        $pdf->SetFont('Arial','',12);
        $pdf->Cell(50,8,'Food Item',1);
        $pdf->Cell(30,8,'Weight (g)',1);
        $pdf->Cell(30,8,'Carbs (g)',1);
        $pdf->Ln();

        // Load items
        $item_sql = "SELECT * FROM carb_record_items WHERE record_id = '".$carb['id']."'";
        $item_res = mysqli_query($conn, $item_sql);

        while($item = mysqli_fetch_assoc($item_res)) {
            $pdf->Cell(50,8,$item['item_name'],1);
            $pdf->Cell(30,8,$item['weight'],1);
            $pdf->Cell(30,8,$item['carbs'],1);
            $pdf->Ln();
        }

        // Insulin Info
        $pdf->Cell(0,8,"Insulin A: ".$carb['insulin_a']." | Insulin B: ".$carb['insulin_b']." | Total: ".$carb['total_insulin'],0,1);
        $pdf->Ln(5);
    }
}


// ------------------------------
// Output PDF
// ------------------------------
$pdf->Output('D','diabetic_report_'.$user['id'].'.pdf');

?>

<?php


// Database connection (same folder)
require_once 'dbconnect.php';

// Get the selected date or use today's date
$date = $_GET['dateSelection'] ?? date('Y-m-d');

$response = [
    'success' => false,
    'message' => '',
    'rentals' => 0,
    'sales' => 0,
    'dateUsed' => $date
];

try {
    // Count rentals for the date
    $rentalsQuery = "SELECT COUNT(*) as rental_count FROM rental WHERE DATE(addedDate) = ?";
    $stmt = mysqli_prepare($con, $rentalsQuery);
    mysqli_stmt_bind_param($stmt, "s", $date);
    mysqli_stmt_execute($stmt);
    $rentalsResult = mysqli_stmt_get_result($stmt);
    $rentalsData = mysqli_fetch_assoc($rentalsResult);
    $response['rentals'] = (int)$rentalsData['rental_count'];
    mysqli_stmt_close($stmt);

    // Calculate total sales for the date
    $salesQuery = "SELECT SUM(total) as total_sales FROM rental WHERE DATE(addedDate) = ?";
    $stmt = mysqli_prepare($con, $salesQuery);
    mysqli_stmt_bind_param($stmt, "s", $date);
    mysqli_stmt_execute($stmt);
    $salesResult = mysqli_stmt_get_result($stmt);
    $salesData = mysqli_fetch_assoc($salesResult);
    $response['sales'] = (float)($salesData['total_sales'] ?? 0);
    mysqli_stmt_close($stmt);

    $response['success'] = true;
    
} catch (Exception $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

// Close connection and return response
mysqli_close($con);
echo json_encode($response);
?>
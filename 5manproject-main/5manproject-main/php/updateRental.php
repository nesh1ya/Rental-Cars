<?php
include('dbconnect.php');

$id = $_POST['rentalID'];
$cID = $_POST['customerID'];

// First validate the customer exists
$check = $con->prepare("SELECT 1 FROM customerinfo WHERE customerID = ?");
$check->bind_param("i", $cID);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Customer does not exist'
    ]));
}

// Then proceed with update
$sql = "UPDATE rental SET 
        customerID = ?,
        carType = ?,
        ratePerDay = ?,
        numberOfDays = ?,
        total = ?,
        dateStart = ?,
        dateEnd = ?
        WHERE rentalID = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param("isdidssi", 
    $_POST['customerID'],
    $_POST['carType'],
    $_POST['rate'],
    $_POST['numberOfDays'],
    $_POST['total'],
    $_POST['dateStart'],
    $_POST['dateEnd1'],
    $_POST['rentalID']
);

if ($stmt->execute()) {
    echo "<script>alert('Rental Updated successfully!'); window.location.href='homepage.php';</script>";
} 
else {
    echo "Error: " . $stmt->error;
}

?>

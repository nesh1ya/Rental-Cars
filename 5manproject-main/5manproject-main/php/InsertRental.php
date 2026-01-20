<?php
    include 'dbconnect.php';
    session_start();
    date_default_timezone_set('Asia/Manila');
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $customerID = $_POST['customerID'];
        $carType = $_POST['carType'];
        $ratePerDay = $_POST['rate'];
        $numberOfDays = $_POST['numberOfDays'];
        $total = $_POST['total'];
        $dateStart = $_POST['dateStart'];
        $dateEnd = $_POST['dateEnd1'];
        $addedDate = date('Y-m-d');

        $email = $_SESSION['email'];

        $sql_user = "SELECT username FROM accounts WHERE email = ?";
        $stmt_user = $con->prepare($sql_user);
        $stmt_user->bind_param("s", $email);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        $row_user = $result_user->fetch_assoc();
        $added_by = $row_user['username'];

        $stmt = $con->prepare("INSERT INTO rental (customerID, carType, ratePerDay, numberOfDays, total, dateStart, dateEnd, addedBy, addedDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issidssss", $customerID, $carType, $ratePerDay, $numberOfDays, $total, $dateStart, $dateEnd, $added_by, $addedDate);

        if ($stmt->execute()) {
            echo "<script>alert('Rental added successfully!'); window.location.href='homepage.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $con->close();
    }


?>

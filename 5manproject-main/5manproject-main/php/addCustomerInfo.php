<?php
    include('dbconnect.php');
    session_start();
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $lname = $_POST['lastName'];
        $fname = $_POST['firstName'];
        $mname = $_POST['middleName'];
        $province = $_POST['province'];
        $city = $_POST['city'];
        $barangay = $_POST['barangay'];
        $detailedAdd = $_POST['detailedAdd'];
        $contact = $_POST['contact'];

        $email = $_SESSION['email'];

        $sql_user = "SELECT username FROM accounts WHERE email = ?";
        $stmt_user = $con->prepare($sql_user);
        $stmt_user->bind_param("s", $email);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        $row_user = $result_user->fetch_assoc();
        $added_by = $row_user['username'];

        $sql = "INSERT INTO customerinfo (lastName, firstName, middleName, province, city, barangay, detailedAddress, contact, addedBy) 
        VALUES ('$lname', '$fname', '$mname', '$province','$city', '$barangay', '$detailedAdd', '$contact','$added_by')";
        
        if (mysqli_query($con, $sql)) {

            echo 
            "<script type='text/javascript'>alert('Details submitted successfully!');
            window.location.href = 'homepage.php';
            </script>";
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }

?>
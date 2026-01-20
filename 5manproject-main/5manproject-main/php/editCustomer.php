<?php
    include('dbconnect.php');

    $id = $_POST['customerID'];
    $lname = $_POST['lastName'];
    $fname = $_POST['firstName'];
    $mname = $_POST['middleName'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $barangay = $_POST['barangay'];
    $detailed = $_POST['detailedAdd'];
    $contact = $_POST['contact'];

    $sql = "UPDATE customerinfo SET LastName=?, FirstName=?, MiddleName=?, province=?, city=?, barangay=?, detailedAddress=?, contact=? WHERE customerID=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssssssi", $lname, $fname, $mname, $province, $city, $barangay, $detailed, $contact, $id);
    $stmt->execute();

    header("Location: homepage.php");
    exit();
?>

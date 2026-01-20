<?php
include("dbconnect.php");
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $query = "DELETE FROM info WHERE id = $id";
    $result = $conn->query($query);

    if($result){
        header('Location: view.php');
    }
    else{
        die(mysqli_error($con));

    }
    $con->close();
}
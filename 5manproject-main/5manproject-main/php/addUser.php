<?php
    include('dbconnect.php');
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        $sql = "INSERT INTO accounts VALUES ('','$email', '$password', '$role', '$username')";
        
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
<?php
    include("dbconnect.php");
    $id = $_GET['id'];
    $sql = "SELECT * FROM info WHERE id='$id'";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $email = $row ['email'];
    $password = $row ['password'];
    $role = $row ['role'];

    if (isset($_POST['update'])){
        $email = $_POST ['email'];
        $password = $_POST ['password'];
        $role = $_POST ['role'];
    
        $sql = "UPDATE info SET email = '$email', password = '$password', role = '$role', WHERE id = '$id'";

        $result = $con->query($sql);

        if ($result){
        header('Location: customer.html');
        } else {
            die(mysqli_error($conn));
        }
        $conn->close();


    }

?>
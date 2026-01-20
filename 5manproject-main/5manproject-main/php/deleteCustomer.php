<?php
    include('dbconnect.php');   
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id = ($_POST['DelID']);
        
        
        $sql1 = "DELETE FROM rental WHERE customerID = ?";
        $stmt1 = $con->prepare($sql1);
        $stmt1->bind_param("i", $id);
        $stmt1->execute();

        $sql = "DELETE FROM customerinfo WHERE customerID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: homepage.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } 
    else{
        echo "Invalid request.";
    }
    $con->close();
?>

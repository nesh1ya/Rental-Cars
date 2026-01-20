<?php
    include('dbconnect.php');   
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id = ($_POST['id']); 


        $sql = "DELETE FROM rental WHERE rentalID = ?";
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
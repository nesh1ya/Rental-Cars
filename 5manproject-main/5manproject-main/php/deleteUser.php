<?php
include('dbconnect.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['DelID'])) {
    $id = $_POST['DelID'];
    $sql = "DELETE FROM accounts WHERE id=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: homepage.php");
        exit();
    } else {
        echo "<script>alert('Delete failed!'); window.history.back();</script>";
    }
    $stmt->close();
}
?>
<?php
            # UPDATE OR EDIT SA MAY "ADD USERS"
include('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $email =$_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // You may want to hash the password in production!
    $sql = "UPDATE accounts SET email=?, username=?, password=?, role=? WHERE id=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssi", $email, $username, $password, $role, $id);

    if ($stmt->execute()) {
        echo "<script>alert('User updated successfully!'); window.location.href='homepage.php';</script>";
    } else {
        echo "<script>alert('Update failed!'); window.history.back();</script>";
    }
    $stmt->close();
}
?>
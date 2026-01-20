<?php
include("dbconnect.php");

if($_SERVER['REQUEST_METHOD']=="POST"){
    $id = $_POST['id'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $hashed_password = password_hash($password,PASSWORD_DEFAULT);
    $query = "INSERT INTO accounts (id, email, password, role) VALUES ('$id','$email','$password','$role')";
    $result = $conn ->query($query);

    if($result){
       // header('Location:view.php');
    }
    else{
        die(mysqli_error($con));
    }
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Create</title>
</head>
<body>
    <h2> Create Account </h2>
    <form action=""method="POST">
        
        <label>Email</label>
        <input type="text" name= "email" placeholder="Email address"><br>
        
        <label>Password</label>
        <input type="password" name= "password" placeholder="Password"><br>
        
        <label>Role</label>
        <input type="text" name= "role" placeholder="Role"><br>
        
        <Button type= "submit" name= "confirm">Confirm</Button>
    </form>
</body>
</html>
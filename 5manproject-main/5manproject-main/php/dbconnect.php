<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "carrentaldb";

$con = mysqli_connect($host, $username, $password, $database);

if (!$con) {
    echo "Error: Unable to connect to MySQL<br>";
    echo "Message: " . mysqli_connect_error() . "<br>";
    exit();
}
?>
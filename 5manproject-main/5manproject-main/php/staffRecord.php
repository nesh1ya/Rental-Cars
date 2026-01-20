 <?php
    include('dbconnect.php');

    $sql = "SELECT COUNT(*) AS count FROM staffrecords";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $generatedId = 2025 . ($row['count'] + 1);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $staffId = $_POST['id'];
        $lastName = $_POST['LastName'];
        $firstName = $_POST['FirstName'];
        $middleInitial = $_POST['MiddleInitial'];
        $address = $_POST['Address'];
        $contactNumber = $_POST['ContactNumber'];
        $monthlySalary = $_POST['Salary'];

        $sql = "INSERT INTO staffrecords 
                VALUES ('$staffId', '$lastName', '$firstName', '$middleInitial', '$address', '$contactNumber', '$monthlySalary')";


        if (mysqli_query($con, $sql)) {
            echo "<script>alert('Staff added successfully!'); window.location.href='homepage.php';</script>";
            exit();
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
?>
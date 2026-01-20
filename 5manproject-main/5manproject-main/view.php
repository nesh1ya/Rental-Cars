<?php
include("dbconnect.php");

$query = "SELECT * FROM accounts";
$result = $con -> query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Accounts</title>
</head>
<body>
    <h1> Account Lists</h1>
<?php
    if($result -> num_rows > 0); ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Password</th>
            <th>Role</th>  
        </tr>
        <?php while ($row = $result -> fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id'])?></td>
                <td><?= htmlspecialchars($row['email'])?></td>
                <td><?= htmlspecialchars($row['password'])?></td>
                <td><?= htmlspecialchars($row['role'])?></td>
                <td><a href = "update.php?id=<?php echo $row['id']?>" class="link">Update</a></td>    
                <td><a href = "delete.php?id=<?php echo $row['id']?>" class="link">Delete</a></td>
            </tr>
        <?php endwhile; ?>    
    </table>


<?php $con -> close(); ?>    
</body>
</html>
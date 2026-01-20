<?php
  include('../php/dbconnect.php');
  session_start();

  if($_SERVER['REQUEST_METHOD'] == "POST"){
      $email = $_POST['email'];
      $password = $_POST['password'];


      $sql = "SELECT * FROM accounts WHERE email = ?";
      $stmt = $con->prepare($sql);
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();
      $user = $result->fetch_assoc(); 
      if ($user) {
          if ($password == $user['password']) {
              $_SESSION['email'] = $email;
              echo "<script>alert('login successfully '); window.location.href = 'homepage.php'</script>";
          } else {
              echo "<script>alert('Wrong Password'); window.location.href = 'index.php'</script>";
          }
    
      }
      else {
          echo "<script>alert('Email or Password is incorrect'); window.location.href = 'index.php'</script>";
      }
  }


?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Form</title>
  <link rel="stylesheet" href="../css/style.css">
</head>


<body>
  <div class="container">
    <div class="left">
      <img src="../assets/download (1).jpg" alt="Car">
    </div>
    <div class="right">
      <h2>LOG IN </h2>
      <p>Car rental services</p>
      <form method="POST" action="">

        <div class="form-group">
          <label for="email">Email *</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>

        <div class="form-group">
            <label for="password">Password *</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <button class="btn" type="submit">Log In</button>

      </form>
    </div>
  </div>
</body>
</html>
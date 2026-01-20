<?php
session_start();
require 'dbconnect.php'; 

$email = $_SESSION['email']; // Use email from session

$oldPassword = $_POST['oldPassword'] ?? '';
$newPassword = $_POST['newPassword'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

// Check if new passwords match
if ($newPassword !== $confirmPassword) {
    echo "<script>
        alert('New passwords do not match.');
        window.history.back();
    </script>";
    exit;
}

// Fetch current password from DB (plain text for now)
$stmt = $con->prepare("SELECT password FROM accounts WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($currentPassword);
$stmt->fetch();
$stmt->close();

if (!$currentPassword || $oldPassword !== $currentPassword) {
    echo "<script>
        alert('Old password is incorrect.');
        window.history.back();
    </script>";
    exit;
}

// Update password (plain text – not recommended for production)
$stmt = $con->prepare("UPDATE accounts SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $newPassword, $email);

if ($stmt->execute()) {
    session_destroy();
    echo "<script>
        alert('Password changed successfully!');
        window.location.href = 'index.php';
    </script>";
    exit;
} else {
    echo "<script>
        alert('Error updating password.');
        window.history.back();
    </script>";
}
$stmt->close();
$con->close();
?>

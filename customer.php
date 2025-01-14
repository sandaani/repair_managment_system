<?php
session_start();

// Redirect to login if the user is not logged in or is not a customer
if (!isset($_SESSION['user']) || $_SESSION['user']['UserType'] !== 'User') {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Include the Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Welcome, <?php echo $user['Username']; ?>!</h1>
        <p>Email: <?php echo $user['Email']; ?></p>
        <p>User Type: <?php echo $user['UserType']; ?></p>

        <!-- Quick Links -->
      
    </div>
</body>
</html>
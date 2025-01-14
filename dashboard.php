<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo $user['Username']; ?>!</h1>
        <p>Email: <?php echo $user['Email']; ?></p>
        <p>User Type: <?php echo $user['UserType']; ?></p>

        <!-- Navigation Links -->
        <div class="navigation">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
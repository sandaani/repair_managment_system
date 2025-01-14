<?php
// Start the session
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include 'db_connect.php';

// Fetch repair statistics based on user type
if ($_SESSION['user']['UserType'] === 'Admin') {
    // Admin can view all repairs
    $sql = "SELECT Status, COUNT(*) as count FROM Repairs GROUP BY Status";
} elseif ($_SESSION['user']['UserType'] === 'Technician') {
    // Technician can view repairs assigned to them
    $technicianID = $_SESSION['user']['UserID'];
    $sql = "SELECT Status, COUNT(*) as count FROM Repairs WHERE TechnicianID='$technicianID' GROUP BY Status";
} else {
    // Customer can view their own repairs
    $userID = $_SESSION['user']['UserID'];
    $sql = "SELECT Status, COUNT(*) as count FROM Repairs WHERE UserID='$userID' GROUP BY Status";
}

// Execute the query
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Include the Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Repair Reports</h2>

        <!-- Display Repair Statistics -->
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['Status']; ?></td>
                        <td><?php echo $row['count']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Back to Dashboard Link -->
        <p>
            <?php if ($_SESSION['user']['UserType'] === 'Admin'): ?>
                <a href="admin.php">Back to Dashboard</a>
            <?php elseif ($_SESSION['user']['UserType'] === 'Technician'): ?>
                <a href="technician.php">Back to Dashboard</a>
            <?php else: ?>
                <a href="customer.php">Back to Dashboard</a>
            <?php endif; ?>
        </p>
    </div>
</body>
</html>
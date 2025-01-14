<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['UserType'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

// Fetch all repairs
$sql = "SELECT * FROM Repairs";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reports</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>All Repair Requests</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Repair ID</th>
                        <th>Device Type</th>
                        <th>Issue Description</th>
                        <th>Customer Name</th>
                        <th>Contact Details</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['RepairID']; ?></td>
                            <td><?php echo $row['DeviceType']; ?></td>
                            <td><?php echo $row['IssueDescription']; ?></td>
                            <td><?php echo $row['CustomerName']; ?></td>
                            <td><?php echo $row['ContactDetails']; ?></td>
                            <td><?php echo $row['Status']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No repairs found.</p>
        <?php endif; ?>
        <p><a href="admin.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
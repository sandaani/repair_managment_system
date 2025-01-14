<?php
// Start the session
session_start();

// Redirect to login if the user is not logged in or is not an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['UserType'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Include the database connection
include 'db_connect.php';

// Handle repair restoration
if (isset($_GET['restore_repair'])) {
    $repairID = $_GET['restore_repair'];
    $restoreQuery = "UPDATE Repairs SET Deleted=FALSE WHERE RepairID='$repairID'";
    if ($conn->query($restoreQuery) === TRUE) {
        $success = "Repair restored successfully!";
    } else {
        $error = "Error restoring repair: " . $conn->error;
    }
}

// Fetch deleted repairs
$sql = "SELECT * FROM Repairs WHERE Deleted=TRUE";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restore Deleted Repairs</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Include the Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Restore Deleted Repairs</h2>

        <!-- Display Success or Error Messages -->
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Deleted Repairs Table -->
        <table>
            <thead>
                <tr>
                    <th>Repair ID</th>
                    <th>Device Type</th>
                    <th>Issue Description</th>
                    <th>Customer Name</th>
                    <th>Contact Details</th>
                    <th>Status</th>
                    <th>Actions</th>
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
                        <td>
                            <a href="restore_repairs.php?restore_repair=<?php echo $row['RepairID']; ?>">Restore</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Back to Dashboard Link -->
     
    </div>
</body>
</html>
<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include 'db_connect.php';

// Fetch repairs based on user type
if ($_SESSION['user']['UserType'] === 'Admin') {
    // Admin can view all repairs
    $sql = "SELECT * FROM Repairs WHERE Deleted=FALSE";
} else {
    // Customer can view their own repairs
    $userID = $_SESSION['user']['UserID'];
    $sql = "SELECT * FROM Repairs WHERE UserID='$userID' AND Deleted=FALSE";
}

// Execute the query
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Repairs</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Include the Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Repair Requests</h2>

        <!-- Display Repairs Table -->
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
                                <?php if ($_SESSION['user']['UserType'] === 'Admin'): ?>
                                    <a href="edit_repair.php?repair_id=<?php echo $row['RepairID']; ?>">Edit</a>
                                    <a href="view_repairs.php?delete_repair=<?php echo $row['RepairID']; ?>" onclick="return confirm('Are you sure you want to delete this repair?')">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No repairs found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
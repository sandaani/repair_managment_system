<?php
session_start();

// Redirect to login if the user is not logged in or is not an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['UserType'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Include the database connection
include 'db_connect.php';

$repairID = $_GET['repair_id'];

// Fetch repair details
$sql = "SELECT * FROM Repairs WHERE RepairID='$repairID'";
$result = $conn->query($sql);
$repair = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $deviceType = $conn->real_escape_string($_POST['deviceType']);
    $issueDescription = $conn->real_escape_string($_POST['issueDescription']);
    $customerName = $conn->real_escape_string($_POST['customerName']);
    $contactDetails = $conn->real_escape_string($_POST['contactDetails']);
    $status = $conn->real_escape_string($_POST['status']);

    // Update the repair in the database
    $updateQuery = "UPDATE Repairs SET 
                    DeviceType='$deviceType', 
                    IssueDescription='$issueDescription', 
                    CustomerName='$customerName', 
                    ContactDetails='$contactDetails', 
                    Status='$status' 
                    WHERE RepairID='$repairID'";

    if ($conn->query($updateQuery) === TRUE) {
        $success = "Repair updated successfully!";
    } else {
        $error = "Error updating repair: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Repair</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Include the Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Edit Repair</h2>

        <!-- Display Success or Error Messages -->
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Edit Repair Form -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="deviceType">Device Type</label>
                <input type="text" id="deviceType" name="deviceType" value="<?php echo $repair['DeviceType']; ?>" required>
            </div>
            <div class="form-group">
                <label for="issueDescription">Issue Description</label>
                <textarea id="issueDescription" name="issueDescription" rows="3" required><?php echo $repair['IssueDescription']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="customerName">Customer Name</label>
                <input type="text" id="customerName" name="customerName" value="<?php echo $repair['CustomerName']; ?>" required>
            </div>
            <div class="form-group">
                <label for="contactDetails">Contact Details</label>
                <input type="text" id="contactDetails" name="contactDetails" value="<?php echo $repair['ContactDetails']; ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="Pending" <?php echo ($repair['Status'] === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="In Progress" <?php echo ($repair['Status'] === 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                    <option value="Completed" <?php echo ($repair['Status'] === 'Completed') ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>
            <button type="submit">Update Repair</button>
        </form>

        <!-- Back to Repairs Link -->
        <p><a href="view_repairs.php">Back to Repairs</a></p>
    </div>
</body>
</html>
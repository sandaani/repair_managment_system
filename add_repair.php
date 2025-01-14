<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include 'db_connect.php';

// Initialize variables for success/error messages
$success = "";
$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $deviceType = $conn->real_escape_string($_POST['deviceType']);
    $issueDescription = $conn->real_escape_string($_POST['issueDescription']);
    $customerName = $conn->real_escape_string($_POST['customerName']);
    $contactDetails = $conn->real_escape_string($_POST['contactDetails']);
    $userID = $_SESSION['user']['UserID']; // Get the logged-in user's ID

    // Insert the new repair into the database (default status is 'Pending')
    $sql = "INSERT INTO Repairs (DeviceType, IssueDescription, CustomerName, ContactDetails, UserID, Status) 
            VALUES ('$deviceType', '$issueDescription', '$customerName', '$contactDetails', '$userID', 'Pending')";

    if ($conn->query($sql) === TRUE) {
        $success = "Repair added successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Repair</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Include the Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Add Repair</h2>

        <!-- Display Success or Error Messages -->
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Add Repair Form -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="deviceType">Device Type</label>
                <input type="text" id="deviceType" name="deviceType" required>
            </div>
            <div class="form-group">
                <label for="issueDescription">Issue Description</label>
                <textarea id="issueDescription" name="issueDescription" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="customerName">Customer Name</label>
                <input type="text" id="customerName" name="customerName" required>
            </div>
            <div class="form-group">
                <label for="contactDetails">Contact Details</label>
                <input type="text" id="contactDetails" name="contactDetails" required>
            </div>
            <button type="submit">Add Repair</button>
        </form>

        <!-- Back to Dashboard Link -->
      
    </div>
</body>
</html>
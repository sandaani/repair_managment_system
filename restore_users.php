<?php
session_start();

// Redirect to login if the user is not logged in or is not an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['UserType'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

// Handle user restoration
if (isset($_GET['restore_user'])) {
    $userID = $_GET['restore_user'];
    $restoreQuery = "UPDATE Users SET Deleted=FALSE WHERE UserID='$userID'";
    if ($conn->query($restoreQuery) === TRUE) {
        $success = "User restored successfully!";
    } else {
        $error = "Error restoring user: " . $conn->error;
    }
}

// Fetch deleted users
$sql = "SELECT * FROM Users WHERE Deleted=TRUE";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restore Deleted Users</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Include the Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Restore Deleted Users</h2>

        <!-- Display Success or Error Messages -->
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Deleted Users Table -->
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['UserID']; ?></td>
                        <td><?php echo $row['Username']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['UserType']; ?></td>
                        <td>
                            <a href="restore_users.php?restore_user=<?php echo $row['UserID']; ?>">Restore</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
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

// Handle user deletion (soft delete)
if (isset($_GET['delete_user'])) {
    $userID = $_GET['delete_user'];
    $deleteQuery = "UPDATE Users SET Deleted=TRUE WHERE UserID='$userID'";
    if ($conn->query($deleteQuery) === TRUE) {
        $success = "User deleted successfully!";
    } else {
        $error = "Error deleting user: " . $conn->error;
    }
}

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

// Fetch all users (excluding deleted ones by default)
$sql = "SELECT * FROM Users WHERE Deleted=FALSE";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Include the Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Manage Users</h2>

        <!-- Display Success or Error Messages -->
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Users Table -->
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
                            <a href="edit_user.php?user_id=<?php echo $row['UserID']; ?>">Edit</a>
                            <a href="manage_users.php?delete_user=<?php echo $row['UserID']; ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Restore Deleted Users Link -->
        <p><a href="restore_users.php">Restore Deleted Users</a></p>

      
    </div>
</body>
</html>
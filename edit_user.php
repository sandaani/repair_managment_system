<?php
session_start();

// Redirect to login if the user is not logged in or is not an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['UserType'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

$userID = $_GET['user_id'];

// Fetch user details
$sql = "SELECT * FROM Users WHERE UserID='$userID'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $userType = $conn->real_escape_string($_POST['userType']);

    $updateQuery = "UPDATE Users SET Username='$username', Email='$email', UserType='$userType' WHERE UserID='$userID'";
    if ($conn->query($updateQuery) === TRUE) {
        $success = "User updated successfully!";
    } else {
        $error = "Error updating user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Include the Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Edit User</h2>

        <!-- Display Success or Error Messages -->
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Edit User Form -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo $user['Username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $user['Email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="userType">User Type</label>
                <select id="userType" name="userType" required>
                    <option value="Admin" <?php echo ($user['UserType'] === 'Admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="User" <?php echo ($user['UserType'] === 'User') ? 'selected' : ''; ?>>User</option>
                </select>
            </div>
            <button type="submit">Update User</button>
        </form>
    </div>
</body>
</html>
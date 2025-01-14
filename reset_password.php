<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_GET['token'];
    $newPassword = password_hash($_POST['newPassword'], PASSWORD_BCRYPT);

    // Verify the token and expiry
    $sql = "SELECT * FROM Users WHERE ResetToken='$token' AND ResetExpiry > NOW()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userID = $user['UserID'];

        // Update the password and clear the token
        $updateQuery = "UPDATE Users SET Password='$newPassword', ResetToken=NULL, ResetExpiry=NULL WHERE UserID='$userID'";
        $conn->query($updateQuery);

        $success = "Password reset successfully. <a href='login.php'>Login</a>";
    } else {
        $error = "Invalid or expired token.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="center-container">
        <div class="form-container">
            <h2>Reset Password</h2>
            <?php if (isset($success)): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                </div>
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
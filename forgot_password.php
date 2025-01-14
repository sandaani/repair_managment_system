<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);

    // Check if the email exists in the database
    $sql = "SELECT * FROM Users WHERE Email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Store the token in the database
        $user = $result->fetch_assoc();
        $userID = $user['UserID'];
        $updateQuery = "UPDATE Users SET ResetToken='$token', ResetExpiry='$expiry' WHERE UserID='$userID'";
        $conn->query($updateQuery);

        // Send an email with the reset link
        $resetLink = "http://yourdomain.com/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Click the link to reset your password: $resetLink";
        mail($email, $subject, $message);

        $success = "A password reset link has been sent to your email.";
    } else {
        $error = "Email not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="center-container">
        <div class="form-container">
            <h2>Forgot Password</h2>
            <?php if (isset($success)): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit">Reset Password</button>
            </form>
            <p><a href="login.php">Back to Login</a></p>
        </div>
    </div>
</body>
</html>
<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Fetch user from the database
    $sql = "SELECT * FROM Users WHERE Username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if the user is an admin
        if ($user['UserType'] === 'Admin') {
            // For admin, compare passwords directly (no hashing)
            if ($password === $user['Password']) {
                // Login successful
                $_SESSION['user'] = $user;
                header("Location: admin.php");
                exit();
            } else {
                $error = "Invalid password for admin!";
            }
        } else {
            // For non-admin users, use password_verify
            if (password_verify($password, $user['Password'])) {
                // Login successful
                $_SESSION['user'] = $user;

                // Redirect based on user type
                if ($user['UserType'] === 'Technician') {
                    header("Location: technician.php");
                } else {
                    header("Location: customer.php");
                }
                exit();
            } else {
                $error = "Invalid password!";
            }
        }
    } else {
        $error = "User not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="center-container">
        <div class="form-container">
            <h2>Login</h2>
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
            <p><a href="forgot_password.php">Forgot Password?</a></p>
        </div>
    </div>
</body>
</html>
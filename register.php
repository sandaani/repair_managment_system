<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone = $conn->real_escape_string($_POST['phone']);

    // Default user type is 'User'
    $userType = 'User';

    // Check if the username or email already exists
    $checkQuery = "SELECT * FROM Users WHERE Username='$username' OR Email='$email'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        $error = "Username or email already exists!";
    } else {
        // Insert the new user into the database
        $insertQuery = "INSERT INTO Users (Username, Email, Password, Phone, UserType) 
                        VALUES ('$username', '$email', '$password', '$phone', '$userType')";

        if ($conn->query($insertQuery) === TRUE) {
            $success = "Registration successful! Redirecting to login...";
            header("Refresh: 2; url=login.php");
            exit();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="center-container">
        <div class="form-container">
            <h2>Register</h2>
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone">
                </div>
                <!-- User Type is fixed as 'User' -->
                <input type="hidden" name="userType" value="User">
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
<?php
// Start the session (if not already started)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Determine the user type
$userType = $_SESSION['user']['UserType'];
?>

<!-- Fixed Left Sidebar -->
<div class="sidebar">
    <h2><?php echo ucfirst($userType); ?> Panel</h2>
    
    <!-- Dashboard Link -->
    <a href="<?php echo ($userType === 'Admin') ? 'admin.php' : 'customer.php'; ?>">Dashboard</a>
    
    <?php if ($userType === 'Admin'): ?>
        <a href="view_repairs.php">View Repairs</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="restore_repairs.php">Restore Repairs</a>
        <a href="reports.php">Generate Reports</a>
    <?php else: ?>
        <a href="add_repair.php">Add Repair</a>
        <a href="view_repairs.php">View Repairs</a>
        <a href="reports.php">Generate Reports</a>
    <?php endif; ?>
    
    <a href="logout.php">Logout</a>
</div>
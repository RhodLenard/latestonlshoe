<?php
// Start the session
session_start();

// Check if the user is an admin
if (isset($_SESSION['admin_id'])) {
    // Unset only the admin-specific session data
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
}

// Redirect to the admin login page
header("location:../admin/admin_index.php");
exit();
?>
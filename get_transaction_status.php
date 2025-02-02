<?php
// Start a session
session_start();

// Include the database connection file
include("db/dbconn.php");

// Check if the transaction ID is provided in the URL
if (!isset($_GET['tid'])) {
    die("Transaction ID not provided.");
}

// Sanitize and validate the transaction ID
$transaction_id = (int)$_GET['tid']; // Cast to integer for safety
if ($transaction_id <= 0) {
    die("Invalid transaction ID.");
}

// Fetch the transaction status from the database
$stmt = $conn->prepare("SELECT order_stat FROM transaction WHERE transaction_id = ?");
$stmt->bind_param("i", $transaction_id); // Bind the transaction ID to the query
$stmt->execute(); // Execute the query
$stmt->bind_result($order_stat); // Bind the result to $order_stat
$stmt->fetch(); // Fetch the result
$stmt->close(); // Close the statement

// Debugging: Log the fetched status (optional)
error_log("Fetched order status for transaction ID $transaction_id: $order_stat");

// Redirect based on the transaction status
if ($order_stat === 'Paid') {
    // If the status is 'Paid', redirect to success.php
    header("Location: success.php?tid=$transaction_id");
    exit;
} elseif ($order_stat === 'Pending') {
    // If the status is 'Pending', redirect to pending.php
    header("Location: display_qr_code.php?tid=$transaction_id");
    exit;
} else {
    // If the status is unknown or invalid, show an error message
    die("Invalid transaction status: $order_stat");
}

// If no redirection happens, return the status as JSON (optional)
// This part will only execute if the above conditions are not met
echo json_encode(['status' => $order_stat]);
?>
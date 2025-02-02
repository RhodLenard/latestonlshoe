<?php
session_start();
include("db/dbconn.php");

if (!isset($_GET['tid'])) {
    die("Transaction ID not provided.");
}

$transaction_id = (int)$_GET['tid']; // Cast to integer for safety
if ($transaction_id <= 0) {
    die("Invalid transaction ID.");
}

// Fetch transaction status
$stmt = $conn->prepare("SELECT order_stat FROM transaction WHERE transaction_id = ?");
$stmt->bind_param("i", $transaction_id);
$stmt->execute();
$stmt->bind_result($order_stat);
$stmt->fetch();
$stmt->close();

// Return the status as JSON
echo json_encode(['status' => $order_stat]);
?>
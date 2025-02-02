<?php
include("../db/dbconn.php");

if (isset($_GET['id']) && isset($_GET['action'])) {
    $trans_id = (int)$_GET['id'];
    $action = $_GET['action'];

    // Validate action
    $valid_actions = ['confirm', 'cancel'];
    if (!in_array($action, $valid_actions)) {
        header("Location: transaction.php");
        exit();
    }

    // Update order status
    $new_status = ($action === 'confirm') ? 'Confirmed' : 'Cancelled';
    $stmt = $conn->prepare("UPDATE transaction SET order_stat = ? WHERE transaction_id = ?");
    $stmt->bind_param("si", $new_status, $trans_id);
    $stmt->execute();
    $stmt->close();

    // Update stock only for confirmed orders
    if ($action === 'confirm') {
        // Get transaction items
        $items_stmt = $conn->prepare("
            SELECT product_id, product_size, quantity 
            FROM transaction_detail 
            WHERE transaction_id = ?
        ");
        $items_stmt->bind_param("i", $trans_id);
        $items_stmt->execute();
        $result = $items_stmt->get_result();

        while ($item = $result->fetch_assoc()) {
            // Update stock for each item
            $update_stmt = $conn->prepare("
                UPDATE stock 
                SET qty = qty - ? 
                WHERE product_id = ? 
                AND product_size = ?
            ");
            $update_stmt->bind_param(
                "iis",
                $item['quantity'],
                $item['product_id'],
                $item['product_size']
            );
            $update_stmt->execute();
            $update_stmt->close();
        }
        $items_stmt->close();
    }

    header("Location: transaction.php");
    exit();
}

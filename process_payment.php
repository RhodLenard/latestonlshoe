<?php
session_start();
include("db/dbconn.php");

// Check if payment confirmation is submitted
if (!isset($_POST['confirm_payment'])) {
    header("Location: cart.php");
    exit;
}

$transaction_id = (int)$_POST['transaction_id'];
$amount = (float)$_POST['amount'];
$payment_method = $_POST['payment_method']; // GCash, Card, or Cash

// Validate the payment method
if (!in_array($payment_method, ['GCash', 'Card', 'Cash'])) {
    die("Invalid payment method.");
}

// PayMongo API credentials
$secret_key = 'sk_test_nXFb87e78U2sHsK4yN8TAusV'; // Replace with your actual secret key

// Handle GCash Payment
if ($payment_method === 'GCash') {
    $success_url = 'https://www.sneakersstreets.com/success.php?tid=' . $transaction_id;
    $failed_url = 'https://www.sneakersstreets.com/failed.php?tid=' . $transaction_id;

    $payload = [
        'data' => [
            'attributes' => [
                'type' => 'gcash',
                'amount' => $amount * 100, // Convert to centavos
                'currency' => 'PHP',
                'redirect' => [
                    'success' => $success_url,
                    'failed' => $failed_url,
                ],
                'metadata' => [
                    'transaction_id' => (string)$transaction_id, // Flat metadata
                ],
            ],
        ],
    ];

    $ch = curl_init('https://api.paymongo.com/v1/sources');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode($secret_key . ':'),
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Log the response for debugging
    error_log("GCash PayMongo Request: " . json_encode($payload));
    error_log("GCash PayMongo Response: " . $response);
    error_log("HTTP Code: " . $http_code);

    if ($http_code !== 200) {
        die("Error creating GCash payment. HTTP Code: $http_code. Response: $response");
    }

    $response_data = json_decode($response, true);
    if (isset($response_data['data']['attributes']['redirect']['checkout_url'])) {
        // Update transaction to 'Pending'
        $stmt = $conn->prepare("UPDATE transaction SET order_stat = ?, payment_method = ? WHERE transaction_id = ?");
        $order_stat = 'Pending';
        $payment_method = 'GCash';
        $stmt->bind_param("ssi", $order_stat, $payment_method, $transaction_id);
        $stmt->execute();
        $stmt->close();

        // Redirect to the GCash checkout page
        $qr_code_url = $response_data['data']['attributes']['redirect']['checkout_url'];
        header("Location: display_qr_code.php?qr_url=" . urlencode($qr_code_url) . "&tid=$transaction_id");
        exit;
    } else {
        die("Error creating GCash payment. Response: " . json_encode($response_data));
    }
}

// Handle Card Payment
if ($payment_method === 'Card') {
    // Step 1: Create a Checkout Session
    $success_url = 'https://www.sneakersstreets.com/success.php?tid=' . $transaction_id;
    $failed_url = 'https://www.sneakersstreets.com/failed.php?tid=' . $transaction_id;

    $payload = [
        'data' => [
            'attributes' => [
                'line_items' => [
                    [
                        'amount' => $amount * 100, // Amount in cents
                        'currency' => 'PHP',
                        'name' => 'Order #' . $transaction_id,
                        'quantity' => 1,
                    ],
                ],
                'payment_method_types' => ['card'],
                'success_url' => $success_url,
                'cancel_url' => $failed_url,
                'description' => 'Payment for Order #' . $transaction_id,
                'metadata' => [
                    'transaction_id' => (string)$transaction_id,
                ],
            ],
        ],
    ];

    $payload_json = json_encode($payload, JSON_THROW_ON_ERROR);

    $ch = curl_init('https://api.paymongo.com/v1/checkout_sessions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode($secret_key . ':'),
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_json);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    error_log("Checkout Session Request: " . $payload_json);
    error_log("Checkout Session Response: " . $response);

    if ($http_code !== 200) {
        die("Error creating checkout session. HTTP Code: $http_code. Response: $response");
    }

    $response_data = json_decode($response, true);

    // Step 2: Redirect the User to the Checkout URL
    if (isset($response_data['data']['attributes']['checkout_url'])) {
        // Update transaction to 'Pending'
        $stmt = $conn->prepare("UPDATE transaction SET order_stat = ?, payment_method = ? WHERE transaction_id = ?");
        $order_stat = 'Pending';
        $payment_method = 'Card';
        $stmt->bind_param("ssi", $order_stat, $payment_method, $transaction_id);
        $stmt->execute();
        $stmt->close();

        // Redirect to the PayMongo Checkout Page
        $checkout_url = $response_data['data']['attributes']['checkout_url'];
        header("Location: " . $checkout_url);
        exit;
    } else {
        die("Error creating checkout session. Response: " . json_encode($response_data));
    }
}

// Handle Cash Payment (Stock Update Removed)
if ($payment_method === 'Cash') {
    $stmt = $conn->prepare("UPDATE transaction SET order_stat = ?, payment_method = ? WHERE transaction_id = ?");
    $order_stat = 'Paid';
    $payment_method = 'Cash';
    $stmt->bind_param("ssi", $order_stat, $payment_method, $transaction_id);
    $stmt->execute();
    $stmt->close();

    // Clear cart session
    unset($_SESSION['cart']);

    // Redirect to success page
    header("Location: cash_payment_success.php?tid=$transaction_id");
    exit;
}
?>
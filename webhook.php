<?php
session_start();
include("db/dbconn.php");
require_once('vendor/autoload.php');

use GuzzleHttp\Client;

// PayMongo API Secret Key
$secretKey = 'sk_test_nXFb87e78U2sHsK4yN8TAusV'; // Replace with your secret key

// Get the raw POST data
$input = file_get_contents('php://input');
$event = json_decode($input, true);

// Log the incoming request for debugging
file_put_contents('webhook.log', "Incoming Webhook Data: " . json_encode($event) . PHP_EOL, FILE_APPEND);

// Validate the event type
if (isset($event['data']['attributes']['type'])) {
    $eventType = $event['data']['attributes']['type'];

    // Handle `source.chargeable` events
    if ($eventType === 'source.chargeable') {
        $sourceId = $event['data']['attributes']['data']['id'];
        $amount = $event['data']['attributes']['data']['attributes']['amount'];
        $metadata = $event['data']['attributes']['data']['attributes']['metadata'];
        $transactionId = $metadata['transaction_id'] ?? null;

        if (!$sourceId || !$transactionId) {
            file_put_contents('webhook.log', "Missing source ID or transaction ID." . PHP_EOL, FILE_APPEND);
            http_response_code(400);
            exit("Missing source ID or transaction ID.");
        }

        // Create a payment using the source ID
        try {
            $client = new Client([
                'base_uri' => 'https://api.paymongo.com/v1/',
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),
                    'Content-Type' => 'application/json',
                ],
            ]);

            $response = $client->post('payments', [
                'json' => [
                    'data' => [
                        'attributes' => [
                            'amount' => $amount,
                            'currency' => 'PHP',
                            'source' => [
                                'id' => $sourceId,
                                'type' => 'source',
                            ],
                        ],
                    ],
                ],
            ]);

            $payment = json_decode($response->getBody(), true);

            // Log the payment response
            file_put_contents('webhook.log', "Payment Created: " . json_encode($payment) . PHP_EOL, FILE_APPEND);

            // Update the transaction in the database
            $stmt = $conn->prepare("UPDATE transaction SET order_stat = 'Paid' WHERE transaction_id = ?");
            $stmt->bind_param("i", $transactionId);
            $stmt->execute();
            $stmt->close();

        } catch (Exception $e) {
            file_put_contents('webhook.log', "Error creating payment: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            http_response_code(500);
            exit("Error creating payment.");
        }
    }

    // Handle `payment.paid` events
    elseif ($eventType === 'payment.paid') {
        // Extract the payment ID
        $paymentId = $event['data']['attributes']['data']['id'] ?? null;

        // Extract the transaction ID from the metadata
        $transactionId = $event['data']['attributes']['data']['attributes']['metadata']['transaction_id'] ?? null;

        if (!$paymentId || !$transactionId) {
            file_put_contents('webhook.log', "Missing payment ID or transaction ID. Payload: " . json_encode($event) . PHP_EOL, FILE_APPEND);
            http_response_code(400);
            exit("Missing payment ID or transaction ID.");
        }

        // Log successful receipt of payment.paid event
        file_put_contents('webhook.log', "Payment Paid Event: Payment ID: $paymentId, Transaction ID: $transactionId" . PHP_EOL, FILE_APPEND);

        // Update the transaction status to 'Paid'
        $stmt = $conn->prepare("UPDATE transaction SET order_stat = 'Paid' WHERE transaction_id = ?");
        $stmt->bind_param("i", $transactionId);
        if ($stmt->execute()) {
            file_put_contents('webhook.log', "Transaction ID: $transactionId updated to 'Paid'." . PHP_EOL, FILE_APPEND);
        } else {
            file_put_contents('webhook.log', "Failed to update transaction ID: $transactionId. Error: " . $stmt->error . PHP_EOL, FILE_APPEND);
        }
        $stmt->close();
    }
}

// Respond with HTTP 200 to acknowledge receipt
http_response_code(200);
echo json_encode(["message" => "Webhook processed successfully"]);
?>
<?php
require_once('vendor/autoload.php');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// PayMongo API credentials
$secret_key = 'sk_test_nXFb87e78U2sHsK4yN8TAusV'; // Replace with your actual secret key

// Webhook details
$webhook_url = 'https://www.sneakersstreets.com/webhook.php'; // Replace with your webhook endpoint URL
$webhook_description = 'GCash Payment Webhook'; // Description for the webhook
$webhook_events = ['source.chargeable', 'payment.paid']; // Events to listen for

// Create the client
$client = new Client([
    'base_uri' => 'https://api.paymongo.com',
    'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Basic ' . base64_encode($secret_key . ':'),
    ],
    'verify' => false, // Disable SSL verification
]);

try {
    // Send the request to create the webhook
    $response = $client->post('/v1/webhooks', [
        'json' => [
            'data' => [
                'attributes' => [
                    'url' => $webhook_url,
                    'description' => $webhook_description,
                    'events' => $webhook_events,
                ],
            ],
        ],
    ]);

    // Decode the response
    $response_data = json_decode($response->getBody(), true);

    // Output the webhook details
    echo "Webhook created successfully!\n";
    echo "Webhook ID: " . $response_data['data']['id'] . "\n";
    echo "Webhook URL: " . $response_data['data']['attributes']['url'] . "\n";
    echo "Events: " . implode(', ', $response_data['data']['attributes']['events']) . "\n";
} catch (RequestException $e) {
    // Handle errors
    echo "Error creating webhook: " . $e->getMessage() . "\n";
    if ($e->hasResponse()) {
        echo "Response: " . $e->getResponse()->getBody() . "\n";
    }
}
?>
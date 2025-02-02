<?php
session_start();
include("db/dbconn.php");

// Debugging: Log the session and POST data
error_log("Session Data: " . print_r($_SESSION, true));
error_log("POST Data: " . print_r($_POST, true));

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Get the transaction ID from the form submission
if (isset($_POST['confirm_payment'])) {
    $transaction_id = (int)$_POST['transaction_id'];
    $amount = (float)$_POST['amount'];

    // Debugging: Log the transaction details
    error_log("Transaction ID: $transaction_id");
    error_log("Amount: $amount");

    // Update the payment status in the database
    $updateQuery = $conn->query("UPDATE transaction SET payment_method = 'GCash', order_stat = 'Paid' WHERE transaction_id = $transaction_id") or die(mysqli_error($conn));
    if (!$updateQuery) {
        die("Error updating transaction status: " . mysqli_error($conn));
    }

    // Fetch transaction details
    $query = $conn->query("SELECT * FROM transaction WHERE transaction_id = $transaction_id") or die(mysqli_error($conn));
    if ($query->num_rows === 0) {
        die("Transaction not found.");
    }
    $transaction = $query->fetch_assoc();

    // Fetch customer details
    $customer_id = $transaction['customerid'];
    $query = $conn->query("SELECT * FROM customer WHERE customerid = $customer_id") or die(mysqli_error($conn));
    if ($query->num_rows === 0) {
        die("Customer not found.");
    }
    $customer = $query->fetch_assoc();
} else {
    // Redirect if no transaction ID is provided
    header("Location: cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>
<body>
<div id="header">
    <img src="img/logo.jpg">
    <label>Sneakers Street</label>
</div>

<div id="container">
    <div class="nav">
        <ul>
            <li><a href="home.php"><i class="icon-home"></i>Home</a></li>
            <li><a href="product1.php"><i class="icon-th-list"></i>Product</a></li>
            <li><a href="aboutus1.php"><i class="icon-bookmark"></i>About Us</a></li>
            <li><a href="contactus1.php"><i class="icon-inbox"></i>Contact Us</a></li>
            <li><a href="privacy1.php"><i class="icon-info-sign"></i>Privacy Policy</a></li>
            <li><a href="faqs1.php"><i class="icon-question-sign"></i>FAQs</a></li>
        </ul>
    </div>

    <div class="well" style="background-color:#fff;">
        <h2>Payment Successful</h2>
        <p>Thank you for your payment! Your order has been processed successfully.</p>

        <h3>Transaction Details</h3>
        <p><strong>Transaction ID:</strong> <?php echo $transaction['transaction_id']; ?></p>
        <p><strong>Customer Name:</strong> <?php echo $customer['firstname'] . ' ' . $customer['lastname']; ?></p>
        <p><strong>Total Amount:</strong> Php <?php echo number_format($amount, 2); ?></p>
        <p><strong>Payment Method:</strong> GCash</p>

        <a href="home.php" class="btn btn-inverse btn-lg">Return to Home</a>
    </div>
</div>

<div id="footer">
    <div class="foot">
        <label style="font-size:17px;"> Copyright &copy; </label>
        <p style="font-size:25px;">Online Shoe Store Inc. 2024 Brought To You by JHARIL JACINTO PINPIN. </p>
    </div>
</div>
</body>
</html>
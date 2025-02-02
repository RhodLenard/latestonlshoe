<?php
session_start();
include("db/dbconn.php");

$qr_url = urldecode($_GET['qr_url']); // QR code URL from PayMongo
$transaction_id = (int)$_GET['tid']; // Transaction ID

// Fetch transaction and customer details
$query = "
    SELECT c.customerid, c.firstname, c.lastname, c.email, c.mobile, t.transaction_id, t.amount, t.order_stat
    FROM customer c
    JOIN transaction t ON c.customerid = t.customerid
    WHERE t.transaction_id = $transaction_id
";
$result = $conn->query($query) or die(mysqli_error($conn));

if ($result->num_rows > 0) {
    $transaction = $result->fetch_assoc();
    $customer_name = $transaction['firstname'] . ' ' . $transaction['lastname'];
    $amount = $transaction['amount'];
    $status = $transaction['order_stat'];
    $email = $transaction['email'];
    $mobile = $transaction['mobile'];
} else {
    die("Transaction not found.");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Sneakers Street</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logo.jpg" />
    <link rel=" stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/loginstyle.css">
    <link rel="stylesheet" href="css/p1.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/disqr.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">
            <img src="images/logo.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
            Sneakers Street
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php
                $id = (int) $_SESSION['id'];
                $query = $conn->query("SELECT * FROM customer WHERE customerid = '$id'") or die(mysqli_error());
                $fetch = $query->fetch_array();
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="account.php"><i class="icon-user"></i> <?php echo $fetch['firstname']; ?> <?php echo $fetch['lastname']; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">
                        <i class="fas fa-shopping-cart">
                            <p style="display: inline; font:message-box;">Cart</p>
                        </i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="function/logout.php"><i class="icon-off"></i> Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div id="container">
        <div class="nav">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="product1.php" class="active">Product</a></li>
                <li><a href="aboutus1.php">About Us</a></li>
                <li><a href="contactus1.php">Contact Us</a></li>
                <li><a href="privacy1.php">Privacy Policy</a></li>
                <li><a href="faqs1.php">FAQs</a></li>
            </ul>
        </div>
    </div>

    <div class="gcash-container">
        <h2>GCash Payment</h2>
        <p class="text-center">Please complete your payment using GCash.</p>

        <h3>Transaction Details</h3>
        <p class="title">Transaction Information:</p>
        <p class="detail"><strong>Transaction ID:</strong> <?php echo $transaction['transaction_id']; ?></p>
        <p class="detail"><strong>Customer Name:</strong> <?php echo $customer_name; ?></p>
        <p class="detail"><strong>Total Amount:</strong> Php <?php echo number_format($amount, 2); ?></p>
        <p class="detail"><strong>Email:</strong> <?php echo $email; ?></p>
        <p class="detail"><strong>Mobile:</strong> <?php echo $mobile; ?></p>

        <h3>GCash Payment Instructions</h3>
        <ol>
            <li>Open the GCash app on your phone.</li>
            <li>Go to <strong>Scan QR</strong>.</li>
            <li>Scan the QR code below:</li>
        </ol>

        <div class="text-center">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=<?php echo urlencode($qr_url); ?>" alt="GCash QR Code">
            <p><strong>Reference Number:</strong> <?php echo $transaction['transaction_id']; ?></p>
        </div>

        <p class="text-center">Once the payment is completed, you will be redirected to the success page.</p>
    </div>

    <footer>
        &copy; 2025 Payment Portal. All rights reserved.
    </footer>
    </div>

    <div style="padding: 20px;">
        <div id="footer">
            <div class="foot">
                <label style="font-size:17px;"> Copyright &copy; </label>
                <p style="font-size:25px;">Sneakers Street Inc. 2025 </p>
            </div>

            <div id="develop">
                <h4>Developed By:</h4>
                <ul style="list-style-type: none; /* Removes the bullets */">
                    <li>JHARIL JACINTO PINPIN</li>
                    <li>JONATHS URAGA</li>
                    <li>JOSHUA MUSNGI</li>
                    <li>TALLE TUBIG</li>
                </ul>
            </div>
        </div>

        <script>
            function checkPaymentStatus() {
                const transactionId = <?php echo $transaction_id; ?>; // Pass the transaction ID to JavaScript

                fetch(`check_payment_status.php?tid=${transactionId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'Paid') {
                            // Redirect to the success page if payment is successful
                            window.location.href = `success.php?tid=${transactionId}`;
                        } else if (data.status === 'Failed') {
                            // Redirect to the failed page if payment failed
                            window.location.href = `failed.php?tid=${transactionId}`;
                        }
                    })
                    .catch(error => console.error('Error checking payment status:', error));
            }

            // Check payment status every 5 seconds
            setInterval(checkPaymentStatus, 5000);
        </script>


</body>

</html>
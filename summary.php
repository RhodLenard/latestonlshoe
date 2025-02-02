<?php
include("function/session.php");
include("db/dbconn.php");

// Check if the transaction ID is provided
if (!isset($_GET['tid'])) {
    header("Location: cart.php");
    exit;
}

$transaction_id = (int)$_GET['tid'];

// Fetch transaction details
$query = $conn->query("SELECT * FROM transaction WHERE transaction_id = '$transaction_id'") or die(mysqli_error($conn));
if ($query->num_rows == 0) {
    die("Transaction not found.");
}
$transaction = $query->fetch_array();

$amount = $transaction['amount'];
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
    <link rel="stylesheet" href="css/summary.css">
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
                <li><a href="home.php"><i class="icon-home"></i>Home</a></li>
                <li><a href="product1.php" class="active"><i class="icon-th-list"></i>Product</a></li>
                <li><a href="aboutus1.php"><i class="icon-bookmark"></i>About Us</a></li>
                <li><a href="contactus1.php"><i class="icon-inbox"></i>Contact Us</a></li>
                <li><a href="privacy1.php"><i class="icon-info-sign"></i>Privacy Policy</a></li>
                <li><a href="faqs1.php"><i class="icon-question-sign"></i>FAQs</a></li>
            </ul>
        </div>
    </div>


    <form method="post" class="well">
        <table class="table">
            <label>Summary of Order/s</label>
            <thead>
                <tr>
                    <th>
                        <h5>Quantity</h5>
                    </th>
                    <th>
                        <h5>Product Name</h5>
                    </th>
                    <th>
                        <h5>Size</h5>
                    </th>
                    <th>
                        <h5>Price</h5>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query2 = $conn->query("
                    SELECT 
                        product.product_name,
                        transaction_detail.product_size,
                        product.product_price,
                        transaction_detail.quantity AS order_qty
                    FROM 
                        transaction_detail
                    LEFT JOIN 
                        product ON product.product_id = transaction_detail.product_id
                    WHERE 
                        transaction_detail.transaction_id = '$transaction_id'
                ") or die(mysqli_error($conn));

                while ($row = $query2->fetch_array()) {
                    $pname = $row['product_name'];
                    $psize = $row['product_size'];
                    $pprice = $row['product_price'];
                    $oqty = $row['order_qty'];

                    echo "<tr>";
                    echo "<td data-label='Quantity'>" . $oqty . "</td>";
                    echo "<td data-label='Product Name'>" . $pname . "</td>";
                    echo "<td data-label='Size'>" . $psize . "</td>";
                    echo "<td data-label='Price'>Php " . number_format($pprice, 2) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <legend></legend>
        <h4>TOTAL: Php <?php echo number_format($amount, 2); ?></h4>
    </form>

    <div class="payment-method-container">
        <form action="process_payment.php" method="post" class="payment-form">
            <input type="hidden" name="transaction_id" value="<?php echo $transaction_id; ?>">
            <input type="hidden" name="amount" value="<?php echo $amount; ?>">

            <h4 class="payment-title">Select Payment Method:</h4>

            <div class="payment-options">
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="GCash" required>
                    <div class="payment-card">
                        <img src="images/gcash-logo.png" alt="GCash Logo" class="payment-logo">
                        <span class="payment-name">GCash</span>
                    </div>
                </label>

                <label class="payment-option">
                    <input type="radio" name="payment_method" value="Card" required>
                    <div class="payment-card">
                        <img src="images/card-logo.png" alt="Card Logo" class="payment-logo">
                        <span class="payment-name">Credit/Debit Card</span>
                    </div>
                </label>

                <label class="payment-option">
                    <input type="radio" name="payment_method" value="Cash" required>
                    <div class="payment-card">
                        <img src="images/cash-payment.png" alt="Cash Logo" class="payment-logo">
                        <span class="payment-name">Cash</span>
                    </div>
                </label>
            </div>

            <br>
            <button type="submit" name="confirm_payment" class="btn-pay-now">
                Confirm Payment
            </button>
        </form>
    </div>
    </div>

    <div style="padding: 20px;">
        <div id="footer">
            <div class="foot">
                <label style="font-size:17px;"> Copyright &copy; </label>
                <p style="font-size:25px;">Sneakers Street Inc. 2025</p>
            </div>
            <div id="develop">
                <h4 style="text-align: center;">Developed By:</h4>
                <ul>
                    <li>JHARIL JACINTO PINPIN</li>
                    <li>JONATHS URAGA</li>
                    <li>JOSHUA MUSNGI</li>
                    <li>TALLE TUBIG</li>
                </ul>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<?php
include("function/session.php");
include("db/dbconn.php");

if (!isset($_GET['tid'])) {
    // Redirect if no transaction ID is provided
    header("Location: cart.php");
    exit;
}

$transaction_id = (int)$_GET['tid']; // Cast to integer for safety
if ($transaction_id <= 0) {
    die("Invalid transaction ID.");
}

// Fetch transaction details using prepared statements
$stmt = $conn->prepare("SELECT * FROM transaction WHERE transaction_id = ?");
$stmt->bind_param("i", $transaction_id);
$stmt->execute();
$transaction = $stmt->get_result()->fetch_assoc();
if (!$transaction) {
    die("Transaction not found.");
}

// Fetch customer details
$customer_id = $transaction['customerid'];
$stmt = $conn->prepare("SELECT * FROM customer WHERE customerid = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();

// Fetch transaction items
$itemsQuery = $conn->query("
    SELECT 
        product.product_name,
        transaction_detail.product_size,
        product.product_price,
        transaction_detail.quantity
    FROM 
        transaction_detail
    LEFT JOIN 
        product ON product.product_id = transaction_detail.product_id
    WHERE 
        transaction_detail.transaction_id = $transaction_id
");
if (!$itemsQuery) {
    die("Error fetching transaction items: " . mysqli_error($conn));
}

// Clear the cart after payment is confirmed
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
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
    <link rel="stylesheet" href="css/sucess.css">
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
        <div class="receipt">
            <h2>Payment Successful</h2>
            <p>Thank you for your payment! Your order has been processed successfully.</p>

            <h3>Transaction Details</h3>
            <p><strong>Transaction ID:</strong> <?php echo $transaction['transaction_id']; ?></p>
            <p><strong>Customer Name:</strong> <?php echo $customer['firstname'] . ' ' . $customer['lastname']; ?></p>
            <p><strong>Total Amount:</strong> Php <?php echo number_format($transaction['amount'], 2); ?></p>
            <p><strong>Payment Method:</strong> PayMongo (GCash/Card)</p>

            <h3>Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    while ($item = $itemsQuery->fetch_assoc()) {
                        $subtotal = $item['product_price'] * $item['quantity'];
                        $total += $subtotal;
                        echo "<tr>
                        <td>{$item['product_name']}</td>
                        <td>{$item['product_size']}</td>
                        <td>{$item['quantity']}</td>
                        <td>Php " . number_format($item['product_price'], 2) . "</td>
                        <td>Php " . number_format($subtotal, 2) . "</td>
                    </tr>";
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                        <td><strong>Php <?php echo number_format($total, 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>

            <div class="text-center mt-4">
                <button onclick="window.print()" class="btn btn-lg btn-success btn-print px-4 py-2 shadow">
                    <i class="fas fa-print"></i> Print Receipt
                </button>
                <a href="home.php" class="btn btn-lg btn-secondary px-4 py-2 shadow">
                    <i class="fas fa-home"></i> Return to Home
                </a>
            </div>

        </div>
    </div>

</body>

</html>
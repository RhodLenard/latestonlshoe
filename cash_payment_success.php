<?php
session_start();
include("db/dbconn.php");

// Get the transaction ID from the URL
if (isset($_GET['tid'])) {
    $transaction_id = (int)$_GET['tid'];

    // Fetch transaction details
    $query = $conn->query("SELECT * FROM transaction WHERE transaction_id = $transaction_id") or die(mysqli_error($conn));
    $transaction = $query->fetch_assoc();

    // Fetch customer details
    $customer_id = $transaction['customerid'];
    $query = $conn->query("SELECT * FROM customer WHERE customerid = $customer_id") or die(mysqli_error($conn));
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
    <title>Cash Payment Success</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- React and Babel -->
    <script src="https://unpkg.com/react@17/umd/react.development.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@17/umd/react-dom.development.js" crossorigin></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logo.jpg" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/loginstyle.css">
    <link rel="stylesheet" href="css/p1.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/newstyle.css">
</head>

<body class="bg-gray-100">
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

    <!-- React App Container -->
    <div id="app" class="container mx-auto p-4"></div>

    <!-- React Component -->
    <script type="text/babel">
        function PaymentSuccess() {
            // Transaction and customer data from PHP
            const transaction = <?php echo json_encode($transaction); ?>;
            const customer = <?php echo json_encode($customer); ?>;

            // Countdown state
            const [countdown, setCountdown] = React.useState(10);

            // Countdown effect
            React.useEffect(() => {
                if (countdown > 0) {
                    const timer = setTimeout(() => setCountdown(countdown - 1), 1000);
                    return () => clearTimeout(timer);
                } else {
                    window.location.href = "home.php";
                }
            }, [countdown]);

            return (
                <div className="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg mt-8">
                    <h2 className="text-2xl font-bold mb-4">Cash Payment Successful</h2>
                    <p className="mb-6 text-gray-700">
                        Thank you for your order! Please prepare the exact amount for cash payment upon delivery.
                    </p>

                    <h3 className="text-xl font-semibold mb-4">Transaction Details</h3>
                    <div className="space-y-2">
                        <p><strong>Transaction ID:</strong> {transaction.transaction_id}</p>
                        <p><strong>Customer Name:</strong> {customer.firstname} {customer.lastname}</p>
                        <p><strong>Total Amount:</strong> Php {parseFloat(transaction.amount).toFixed(2)}</p>
                        <p><strong>Payment Method:</strong> Cash</p>
                    </div>

                    <p className="mt-4 text-gray-600">
                        You will be redirected to the home page in {countdown} seconds...
                    </p>
                    <a
                        href="home.php"
                        className="mt-6 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300"
                    >
                        Return to Home
                    </a>
                </div>
            );
        }

        // Render the React component
        ReactDOM.render(<PaymentSuccess />, document.getElementById('app'));
    </script>
</body>

</html>
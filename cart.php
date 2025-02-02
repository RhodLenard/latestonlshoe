<?php
include("function/session.php");
include("db/dbconn.php");


if (!isset($_SESSION['id'])) {
    $_SESSION['pending_item'] = [
        'product_id' => $_POST['product_id'],
        'size' => $_POST['product_size']
    ];
    $_SESSION['login_error'] = "Please log in to add items to the cart.";
    header("Location: login.php");
    exit();
}

// Handle adding to cart
if (isset($_POST['add_to_cart'])) {
    // Validate and sanitize inputs
    $productId = (int)$_POST['product_id'];
    $size = trim($_POST['product_size']);

    // Check if product ID and size are valid
    if ($productId <= 0 || empty($size)) {
        die("Invalid product or size.");
    }

    // Initialize the cart if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Create a composite key using product_id and size
    $compositeKey = $productId . '_' . $size;

    // Check if the product with the same size already exists in the cart
    if (isset($_SESSION['cart'][$compositeKey])) {
        $_SESSION['cart'][$compositeKey]['quantity'] += 1;
    } else {
        // Add product with size and quantity
        $_SESSION['cart'][$compositeKey] = [
            'product_id' => $productId,
            'size' => $size,
            'quantity' => 1,
        ];
    }

    // Ensure a pending transaction exists
    $customerId = (int)$_SESSION['id']; // Ensure customer ID is valid
    if ($customerId <= 0) {
        die("Invalid customer ID.");
    }

    // Fetch or create a pending transaction
    $transactionQuery = $conn->query("SELECT transaction_id FROM transaction WHERE customerid = $customerId AND order_stat = 'Pending' LIMIT 1");
    if ($transactionQuery->num_rows == 0) {
        // Create a new pending transaction
        $conn->query("INSERT INTO transaction (customerid, order_stat) VALUES ($customerId, 'Pending')") or die(mysqli_error($conn));
        $transactionId = $conn->insert_id; // Get the last inserted ID
    } else {
        $transaction = $transactionQuery->fetch_assoc();
        $transactionId = (int)$transaction['transaction_id'];
    }

    // Update the database (transaction_detail table)
    $conn->query("INSERT INTO transaction_detail (transaction_id, product_id, quantity, product_size) 
                  VALUES ($transactionId, $productId, 1, '$size') 
                  ON DUPLICATE KEY UPDATE quantity = quantity + 1")
        or die(mysqli_error($conn));

    // Redirect to the cart page
    header("Location: cart.php");
    exit;
}

// Handle add and remove actions
if (isset($_GET['id'], $_GET['action'], $_GET['size'])) {
    $productId = (int)$_GET['id'];
    $action = $_GET['action']; // Define $action here
    $size = $_GET['size'];

    // Create a composite key using product_id and size
    $compositeKey = $productId . '_' . $size;

    // Initialize the cart if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Fetch the most recent pending transaction for the customer
    $customerId = $_SESSION['id'];
    $transactionQuery = $conn->query("SELECT transaction_id FROM transaction WHERE customerid = $customerId AND order_stat = 'Pending' LIMIT 1");
    if ($transactionQuery->num_rows > 0) {
        $transaction = $transactionQuery->fetch_assoc();
        $transactionId = $transaction['transaction_id'];

        if ($action === 'add') {
            if (isset($_SESSION['cart'][$compositeKey])) {
                // Increment the quantity in the session
                $_SESSION['cart'][$compositeKey]['quantity'] += 1;

                // Update the quantity in the database (transaction_detail table)
                $quantity = $_SESSION['cart'][$compositeKey]['quantity'];
                $conn->query("UPDATE transaction_detail 
                              SET quantity = $quantity 
                              WHERE product_id = $productId 
                                AND transaction_id = $transactionId
                                AND product_size = '$size'") // Ensure size is included in the query
                    or die("Error updating transaction_detail: " . mysqli_error($conn));

                header("Location: cart.php");
                exit;
            }
        } elseif ($action === 'remove') {
            if (isset($_SESSION['cart'][$compositeKey])) {
                // Decrement the quantity in the session
                $_SESSION['cart'][$compositeKey]['quantity'] -= 1;

                // If quantity reaches zero, remove the item from the session and database
                if ($_SESSION['cart'][$compositeKey]['quantity'] <= 0) {
                    unset($_SESSION['cart'][$compositeKey]);

                    // Delete the item from the database (transaction_detail table)
                    $conn->query("DELETE FROM transaction_detail 
                                  WHERE product_id = $productId 
                                    AND transaction_id = $transactionId
                                    AND product_size = '$size'")
                        or die("Error deleting from transaction_detail: " . mysqli_error($conn));

                    // Check if the transaction has any remaining items
                    $remainingItemsQuery = $conn->query("SELECT COUNT(*) AS item_count FROM transaction_detail WHERE transaction_id = $transactionId");
                    $remainingItems = $remainingItemsQuery->fetch_assoc()['item_count'];

                    // If no items are left in the transaction, delete the transaction
                    if ($remainingItems == 0) {
                        $conn->query("DELETE FROM transaction WHERE transaction_id = $transactionId")
                            or die("Error deleting transaction: " . mysqli_error($conn));
                    }
                } else {
                    // Update the quantity in the database (transaction_detail table)
                    $quantity = $_SESSION['cart'][$compositeKey]['quantity'];
                    $conn->query("UPDATE transaction_detail 
                                  SET quantity = $quantity 
                                  WHERE product_id = $productId 
                                    AND transaction_id = $transactionId
                                    AND product_size = '$size'")
                        or die("Error updating transaction_detail: " . mysqli_error($conn));
                }

                // Redirect to refresh the cart page
                header("Location: cart.php");
                exit;
            } else {
                echo "<script>alert('Item not found in the cart.');</script>";
            }
        }
    } else {
        // No pending transaction found
        echo "<script>alert('No pending transaction found. Please start a new order.');</script>";
        echo "<script>window.location.href = 'cart.php';</script>";
        exit;
    }
}

// Handle payment
if (isset($_POST['pay_now'])) {
    // Check if the cart is empty
    if (empty($_SESSION['cart'])) {
        echo "<script>alert('Your cart is empty. Please add items to your cart before proceeding to payment.');</script>";
        echo "<script>window.location.href = 'cart.php';</script>";
        exit;
    }

    $customer_id = $_SESSION['id'];
    $total_amount = 0;
    date_default_timezone_set('Asia/Manila'); // Set the timezone to Philippines
    $current_date = date('Y-m-d H:i:s'); // Get the current date and time
    echo $current_date;

    // Calculate the total amount
    foreach ($_SESSION['cart'] as $compositeKey => $details) {
        if (!isset($details['product_id']) || empty($details['product_id'])) {
            continue; // Skip this item if product_id is missing
        }

        $productId = $details['product_id'];
        $query = $conn->query("SELECT * FROM product WHERE product_id = $productId") or die(mysqli_error($conn));
        $product = $query->fetch_assoc();
        $total_amount += $product['product_price'] * $details['quantity'];
    }

    // Fetch the pending transaction for the customer
    $transactionQuery = $conn->query("SELECT transaction_id FROM transaction WHERE customerid = $customer_id AND order_stat = 'Pending' LIMIT 1");
    if ($transactionQuery->num_rows > 0) {
        $transaction = $transactionQuery->fetch_assoc();
        $transaction_id = $transaction['transaction_id'];

        // Update the transaction amount and order date (but keep status as 'Pending')
        $conn->query("UPDATE transaction 
                      SET amount = '$total_amount', order_date = '$current_date' 
                      WHERE transaction_id = $transaction_id")
            or die(mysqli_error($conn));
    } else {
        // If no pending transaction exists, create a new one with status 'Pending'
        $conn->query("INSERT INTO transaction (customerid, amount, order_stat, order_date) 
                      VALUES ('$customer_id', '$total_amount', 'Pending', '$current_date')")
            or die(mysqli_error($conn));
        $transaction_id = $conn->insert_id; // Get the last inserted ID
    }

    // Redirect to the payment summary page
    header("Location: summary.php?tid=$transaction_id");
    exit;
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
    <link rel="stylesheet" href="css/cart.css">
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
            <label>My Cart</label>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($_SESSION['cart'])) {
                    $total = 0;
                    foreach ($_SESSION['cart'] as $compositeKey => $details) {
                        if (!isset($details['product_id']) || empty($details['product_id'])) {
                            continue;
                        }

                        $productId = $details['product_id'];
                        $size = $details['size'];
                        $quantity = $details['quantity'];

                        $query = $conn->query("SELECT * FROM product WHERE product_id = $productId") or die(mysqli_error($conn));
                        if ($query->num_rows > 0) {
                            $product = $query->fetch_assoc();

                            $name = $product['product_name'];
                            $price = $product['product_price'];
                            $image = $product['product_image'];

                            $subtotal = $price * $quantity;
                            $total += $subtotal;

                            echo "<tr>
                                <td data-label='Image'><img src='photo/{$image}' alt='{$name}'></td>
                                <td data-label='Product Name'>{$name}</td>
                                <td data-label='Size'>{$size}</td>
                                <td data-label='Quantity'>{$quantity}</td>
                                <td data-label='Price'>₱ {$price}</td>
                                <td data-label='Subtotal'>₱ {$subtotal}</td>
                                <td data-label='Action'>
                                    <a href='cart.php?id={$productId}&size={$size}&action=add' class='btn'>Add</a>
                                    <a href='cart.php?id={$productId}&size={$size}&action=remove' class='btn'>Remove</a>
                                </td>
                            </tr>";
                        }
                    }

                    echo "<tr>
                        <td colspan='5'><strong>Total</strong></td>
                        <td colspan='2'><strong>₱ {$total}</strong></td>
                    </tr>";
                } else {
                    echo "<tr><td colspan='7' class='empty-cart'>Your cart is empty.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="btn-container">
            <a href='home.php' class='btn btn-inverse'>Continue Shopping</a>
            <button name='pay_now' type='submit' class='btn'>Purchase</button>
        </div>
    </form>
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
</body>

</html>
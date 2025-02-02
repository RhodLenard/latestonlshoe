<?php
session_start();
include('db/dbconn.php');

if (isset($_POST['login'])) {
    // Sanitize email input
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Validate inputs
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['login_error'] = "Invalid Email Format!";
        header("Location: login.php");
        exit();
    }
    if (empty($password)) {
        $_SESSION['login_error'] = "Password cannot be empty!";
        header("Location: login.php");
        exit();
    }

    // Limit login attempts (Prevent brute-force)
    $max_attempts = 10;
    $lockout_time = 10 * 60; // 10 minutes
    $current_time = time();

    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = [];
    }

    // Remove old attempts
    $_SESSION['login_attempts'] = array_filter($_SESSION['login_attempts'], function ($attempt) use ($current_time, $lockout_time) {
        return ($current_time - $attempt) < $lockout_time;
    });

    if (count($_SESSION['login_attempts']) >= $max_attempts) {
        $_SESSION['login_error'] = "Too many failed login attempts. Please try again later.";
        header("Location: login.php");
        exit();
    }

    // Check email in database
    $stmt = $conn->prepare("SELECT * FROM customer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

   if ($row) {
        if (password_verify($password, $row['password'])) {
            // Successful login
            $_SESSION['id'] = $row['customerid'];
            $_SESSION['login_attempts'] = [];
            session_regenerate_id(true);

            $customer_id = $_SESSION['id'];
            $redirectPage = "home.php"; // Default redirect page

            // ✅ Step 1: Load Existing Cart from Database
            $query = $conn->query("
                SELECT transaction_id FROM transaction 
                WHERE customerid = $customer_id AND order_stat = 'Pending' LIMIT 1
            ") or die(mysqli_error($conn));

            if ($query->num_rows > 0) {
                $transaction = $query->fetch_assoc();
                $transaction_id = (int)$transaction['transaction_id'];

                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                // Fetch items from transaction_detail
                $cart_query = $conn->query("
                    SELECT product_id, product_size, quantity 
                    FROM transaction_detail 
                    WHERE transaction_id = $transaction_id
                ") or die(mysqli_error($conn));

                while ($row = $cart_query->fetch_assoc()) {
                    $compositeKey = $row['product_id'] . '_' . $row['product_size'];

                    if (isset($_SESSION['cart'][$compositeKey])) {
                        $_SESSION['cart'][$compositeKey]['quantity'] += $row['quantity'];
                    } else {
                        $_SESSION['cart'][$compositeKey] = [
                            'product_id' => $row['product_id'],
                            'size' => $row['product_size'],
                            'quantity' => $row['quantity']
                        ];
                    }
                }
            }

            // ✅ Step 2: Add Pending Item After Logout (if exists)
            if (isset($_SESSION['pending_item'])) {
                $productId = (int)$_SESSION['pending_item']['product_id'];
                $size = trim($_SESSION['pending_item']['size']);
                unset($_SESSION['pending_item']);

                if (!isset($transaction_id)) {
                    $conn->query("INSERT INTO transaction (customerid, order_stat) VALUES ($customer_id, 'Pending')");
                    $transaction_id = $conn->insert_id;
                }

                $conn->query("
                    INSERT INTO transaction_detail (transaction_id, product_id, quantity, product_size) 
                    VALUES ($transaction_id, $productId, 1, '$size') 
                    ON DUPLICATE KEY UPDATE quantity = quantity + 1
                ") or die(mysqli_error($conn));

                $compositeKey = $productId . '_' . $size;
                if (isset($_SESSION['cart'][$compositeKey])) {
                    $_SESSION['cart'][$compositeKey]['quantity'] += 1;
                } else {
                    $_SESSION['cart'][$compositeKey] = [
                        'product_id' => $productId,
                        'size' => $size,
                        'quantity' => 1
                    ];
                }

                $redirectPage = "cart.php"; // Change redirect to cart if an item was added
            }

            // ✅ Step 3: Show Loading Animation & Redirect
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Create full-screen overlay
                    const overlay = document.createElement('div');
                    overlay.style.position = 'fixed';
                    overlay.style.top = '0';
                    overlay.style.left = '0';
                    overlay.style.width = '100%';
                    overlay.style.height = '100%';
                    overlay.style.backgroundColor = 'rgba(255, 255, 255, 0.9)';
                    overlay.style.zIndex = '9999';
                    overlay.style.display = 'flex';
                    overlay.style.flexDirection = 'column';
                    overlay.style.justifyContent = 'flex-start'; // Align spinner at the top
                    overlay.style.alignItems = 'center'; // Center horizontally
                    document.body.appendChild(overlay);

                    // Add loading spinner
                    const loadingSpinner = document.createElement('div');
                    loadingSpinner.style.width = '50px';
                    loadingSpinner.style.height = '50px';
                    loadingSpinner.style.border = '5px solid #f3f3f3';
                    loadingSpinner.style.borderTop = '5px solid #3498db';
                    loadingSpinner.style.borderRadius = '50%';
                    loadingSpinner.style.animation = 'spin 1s linear infinite';
                    loadingSpinner.style.marginTop = '20px';
                    overlay.appendChild(loadingSpinner);

                    // Add logo
                    const logoContainer = document.createElement('div');
                    logoContainer.style.flex = '1';
                    logoContainer.style.display = 'flex';
                    logoContainer.style.justifyContent = 'center';
                    logoContainer.style.alignItems = 'center';
                    logoContainer.style.width = '100%';

                    const logo = document.createElement('img');
                    logo.src = 'images/logo.jpg';
                    logo.alt = 'Logo';
                    logo.style.width = '120px';
                    logo.style.height = 'auto';
                    logo.style.maxWidth = '150px';
                    logoContainer.appendChild(logo);
                    overlay.appendChild(logoContainer);

                    // Add spinner animation
                    const style = document.createElement('style');
                    style.innerHTML = `
                        @keyframes spin {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                    `;
                    document.head.appendChild(style);

                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = '$redirectPage';
                    }, 2000);
                });
            </script>
            ";
            exit();
        } else {
            $_SESSION['login_attempts'][] = time();
            $_SESSION['login_error'] = "Invalid Email or Password.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['login_attempts'][] = time();
        $_SESSION['login_error'] = "Invalid Email or Password.";
        header("Location: login.php");
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">

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
    <link rel="stylesheet" href="css/newstyle.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">
            <img src="images/logo.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
            Sneakers Street
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Corrected structure: Now includes the navigation links inside the collapsible div -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="product.php">Product</a></li>
                <li class="nav-item"><a class="nav-link" href="aboutus.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="contactus.php">Contact Us</a></li>
                <li class="nav-item"><a class="nav-link" href="privacy.php">Privacy Policy</a></li>
                <li class="nav-item"><a class="nav-link" href="faqs.php">FAQs</a></li>
            </ul>
        </div>
    </nav>


    <div id="container">
        <div class="nav">

        </div>
    </div>

    <div id="fcontainer">
        <div class="form-container">
            <h2>Login</h2>
            <?php
            if (isset($_SESSION['login_error'])) {
                echo '<div style="color: red; text-align: center; margin-top: 10px; font-size: 14px;">' . $_SESSION['login_error'] . '</div>';
                unset($_SESSION['login_error']); // Clear the error message after displaying
            }
            ?>
            <form method="POST" action="login.php">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login">Login</button>
            </form>
            <p>Don't have an account? <a href="signup.php" class="signup-link" style="display: inline;"> Sign up here!</a></p>
        </div>
    </div>


    <div style="padding: 20px;">
        <div id="footer">
            <div class="foot">&copy; Sneakers Street Inc. 2025</div>
            <div id="develop">
                <h4>Developed By:</h4>
                <ul>
                    <li>JHARIL JACINTO PINPIN</li>
                    <li>JONATHS URAGA</li>
                    <li>JOSHUA MUSNGI</li>
                    <li>TALLE TUBIG</li>
                </ul>
            </div>
        </div>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Popper.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>

        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
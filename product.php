<?php
include("function/login.php");
include("function/customer_signup.php");
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
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/p1.css">
    <link rel="stylesheet" href="css/plist.css">

</head>

<body>

    <nav class=" navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">
            <img src="images/logo.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
            Sneakers Street
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                    <a class="nav-link" href="login.php">
                        <i class="fas fa-shopping-cart">
                            <p style="display: inline; font:message-box;">Cart</p>
                        </i>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" a href="login.php"><i class="icon-user"></i> Login</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="signup.php"><i class="icon-off"></i>Sign Up</a>
                </li>

            </ul>
        </div>
    </nav>


    <div id="container">
        <div class="nav">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="product.php" class="active">Product</a></li>
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="contactus.php">Contact Us</a></li>
                <li><a href="privacy.php">Privacy Policy</a></li>
                <li><a href="faqs.php">FAQs</a></li>
            </ul>
        </div>
    </div>

    <div class="nav1">
        <ul>
            <li><a href="product.php" class="active">Basketball</a></li>
            <li><a href="football.php">Sneakers</a></li>
            <li><a href="running.php">Running</a></li>
        </ul>
    </div>

    <div id="content">
        <div id="product">
            <?php
            $query = $conn->query("SELECT * FROM product WHERE category='basketball' ORDER BY product_id DESC") or die(mysqli_error());
            $all_out_of_stock = true; // Assume all products are out of stock initially

            while ($fetch = $query->fetch_array()) {
                $pid = $fetch['product_id'];

                // Fetch stock information for the product
                $query1 = $conn->query("SELECT * FROM stock WHERE product_id = '$pid'") or die(mysqli_error());
                $rows = $query1->fetch_array();

                // Check if stock data exists and quantity is greater than 0
                if ($rows && isset($rows['qty']) && $rows['qty'] > 0) {
                    $all_out_of_stock = false; // At least one product is in stock

                    // Display the product if it's in stock
                    echo "<div class='float'>";
                    echo "<a href='details2.php?id=" . $fetch['product_id'] . "'>";
                    echo "<img src='photo/" . $fetch['product_image'] . "' alt='" . $fetch['product_name'] . "'>";
                    echo "<div class='cart-icon' onclick='addToCart(" . $fetch['product_id'] . ")'>";
                    echo "<img src='images/shopping-cart.png' alt='Add to Cart'>"; // Replace with your cart icon
                    echo "</div>";
                    echo "<h3>" . $fetch['product_name'] . "</h3>";
                    echo "<p>P " . $fetch['product_price'] . "</p>";
                    echo "</a>";
                    echo "</div>";
                }
            }

            // If all products are out of stock, display a single "No Stock" message in the center
            if ($all_out_of_stock) {
                echo "<div style='text-align: center; margin-top: 20px;'>";
                echo "<span style='color: red; font-weight: bold; font-size: 18px;'>No Stock</span>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <div style="padding: 20px;">
        <div id="footer">
            <div class="foot">
                <label style="font-size:17px;"> Copyright &copy; </label>
                <p style="font-size:25px;">Sneakers Street Inc. 2025</p>
            </div>
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

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Popper.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>

        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
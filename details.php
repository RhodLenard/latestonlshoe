<?php
include("function/session.php");
include("db/dbconn.php");
include("function/cash.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/newstyle.css">

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




    <?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = $conn->query("SELECT * FROM product WHERE product_id = '$id'");
        $row = $query->fetch_array();

        // Fetch stock levels for each size
        $stockQuery = $conn->query("SELECT product_size, qty FROM stock WHERE product_id = '$id'");
        $stock = [];
        while ($stockRow = $stockQuery->fetch_assoc()) {
            $stock[$stockRow['product_size']] = $stockRow['qty'];
        }
    ?>

        <div id="content">
            <div id="product">
                <center>
                    <img class="product-image img-polaroid" src="photo/<?php echo $row['product_image']; ?>" alt="Product Image">
                    <h2 class="text-uppercase"><?php echo $row['product_name']; ?></h2>
                    <h3 class="text-uppercase">Php <?php echo $row['product_price']; ?></h3>

                    <!-- Size Selection -->
                    <form action="cart.php" method="POST">
                        <h3 class="text-uppercase">Available Size:</h3>
                        <div class="size-container">
                            <?php

                            $sizes = !empty($row['product_size']) ? explode(',', $row['product_size']) : ['No sizes available'];
                            foreach ($sizes as $size) {
                                $size = trim($size);
                                $qty = isset($stock[$size]) ? $stock[$size] : 0;
                                $disabled = ($qty == 0) ? 'disabled' : '';
                                $class = ($qty == 0) ? 'size-option out-of-stock' : 'size-option';

                                // Determine the notification message
                                $stockMessage = '';
                                if ($qty == 0) {
                                    $stockMessage = '<div class="stock-notif out-of-stock-notif">Out of Stock!</div>';
                                } elseif ($qty == 1) {
                                    $stockMessage = '<div class="stock-notif few-stock-notif">Few Stock Left!</div>';
                                }

                                echo "
                                            <div class='size-group'>
                                            <label style='cursor: pointer;'>
                                                <input type='radio' name='product_size' value='$size' style='display: none;' $disabled required>
                                                <div class='$class'>
                                                    $size
                                                </div>
                                            </label>
                                            $stockMessage
                                        </div>";
                            }
                            ?>
                        </div>
                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                        <br><br>
                        <input type="submit" class="btn btn-inverse" name="add_to_cart" value="Add to Cart">
                    </form>
                    <br>
                    <a href='product1.php'><button class='btn btn-inverse'>Back</button></a>
                </center>
            </div>
        <?php } ?>

        <!-- Add CSS and JavaScript -->
        <style>
            /* Default size option styles */
            .size-container {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                max-width: 400px;
                margin: 0 auto;
            }

            .size-option {
                border: 1px solid #ccc;
                padding: 10px 15px;
                text-align: center;
                border-radius: 5px;
                background: #f9f9f9;
                font-weight: bold;
                transition: border-color 0.3s;
                cursor: pointer;
                flex: 1 1 calc(25% - 10px);
                box-sizing: border-box;
            }

            .size-option:hover {
                border-color: rgb(0, 0, 0);
            }

            input[type="radio"]:checked+.size-option {
                border-color: #000;
                background: #f9f9f9;
                color: inherit;
            }

            /* Disabled size option styling */
            .size-option.out-of-stock {
                background: #e0e0e0;
                color: #a0a0a0;
                cursor: not-allowed;
                border-color: #ccc;
            }

            /* Size group container */
            .size-group {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 5px;
                /* Space between size box and notification */
            }

            /* Stock notification styling */
            .stock-notif {
                display: none;
                text-align: center;
                font-size: 10px;
                opacity: 0;
                /* Start fully transparent */
                transition: opacity 0.3s ease;
                /* Smooth fade-in effect */
            }

            /* Out of Stock notification styling */
            .out-of-stock-notif {
                color: #ff4444;
            }

            /* Few Stock Left notification styling */
            .few-stock-notif {
                color: #ff9900;
            }

            /* Show the notification on hover */
            .size-group:hover .stock-notif {
                display: block;
                /* Show the message */
                opacity: 1;
                /* Fade to fully visible */
            }

            /* Style for Add to Cart button with btn-inverse class */
            .btn-inverse {
                background-color: #333;
                /* Dark background */
                color: white;
                /* White text */
                padding: 10px 20px;
                /* Padding for better spacing */
                border: 2px solid #333;
                /* Dark border */
                border-radius: 5px;
                /* Rounded corners */
                font-size: 16px;
                /* Font size */
                cursor: pointer;
                /* Pointer cursor on hover */
                transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
                /* Smooth hover effect */
            }

            /* Hover effect for Add to Cart button */
            .btn-inverse:hover {
                background-color: white;
                /* White background on hover */
                color: #999;
                /* Dark text on hover */
                border-color: #333;
                /* Dark border on hover */
            }
        </style>

        <script>
            // JavaScript to ensure selected state stays consistent visually
            document.querySelectorAll('input[type="radio"]').forEach((radio) => {
                radio.addEventListener('change', () => {
                    // Uncheck other options
                    document.querySelectorAll('.size-option').forEach((option) => {
                        option.classList.remove('selected');
                    });

                    // Mark the selected option
                    if (radio.checked) {
                        radio.nextElementSibling.classList.add('selected');
                    }
                });
            });
        </script>
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
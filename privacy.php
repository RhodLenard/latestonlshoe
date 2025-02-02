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
                <li><a href="index.php"><i class="icon-home"></i>Home</a></li>
                <li><a href="product.php"><i class="icon-th-list"></i>Product</a></li>
                <li><a href="aboutus.php"><i class="icon-bookmark"></i>About Us</a></li>
                <li><a href="contactus.php"><i class="icon-inbox"></i>Contact Us</a></li>
                <li><a href="privacy.php" class="active"><i class="icon-info-sign"></i>Privacy Policy</a></li>
                <li><a href="faqs.php"><i class="icon-question-sign"></i>FAQs</a></li>
            </ul>
        </div>
    </div>

    <div id="content">
        <legend>
            <h3>Privacy Policy</h3>
        </legend>
        <p>The Sneakers Streets respect the privacy of the visitors
            to the <a href="https://sneakersstreets.com/">sneakersstreets.com</a> website and the local websites connected with it, and take great care to protect your
            information.. This privacy policy tells you what information we collect from you, how we may use it and
            the steps we take to ensure that it is protected.
        </p>
        <hr>
        <h4>Protection of visitors information</h4>
        <p>In order to protect the information you provide to us by visiting our website we have implemented various
            security measures. Your personal information is contained behind secured networks and is only accessible
            by a limited number of people, who have special access rights and are required to keep the information
            confidential.Please keep in mind though that whenever you give out personal information online there is a
            risk that third parties may intercept and use that information. While Online Shoe Store strives to protect its user's
            personal information and privacy, we cannot guarantee the security of any information you disclose online
            and you do so at your own risk.</p>
        <hr>
        <h4>Use of cookies</h4>
        <p>A cookie is a small string of information that the website that you visit transfers to your computer for
            identification purposes. Cookies can be used to follow your activity on the website and that information
            helps us to understand your preferences and improve your website experience. Cookies are also used to
            remember for instance your user name and password.</p>
        <p>You can turn off all cookies, in case you prefer not to receive them. You can also have your computer warn
            you whenever cookies are being used. For both options you have to adjust your browser settings
            (like internet explorer). There are also software products available that can manage cookies for you.
            Please be aware though that when you have set your computer to reject cookies, it can limit the
            functionality of the website you visit and it’s possible then that you do not have access to some of the
            features on the website.</p>
        <hr>
        <h4>Online policy</h4>
        <p>The Privacy Policy does not extend to anything that is inherent in the operation of the internet, and
            therefore beyond adidas' control, and is not to be applied in any manner contrary to applicable law or
            governmental regulation. This online privacy policy only applies to information collected through our
            website and not to information collected offline.</p>
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
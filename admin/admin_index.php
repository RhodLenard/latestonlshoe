<?php
session_start(); // Start the session before using session variables
include('../db/dbconn.php'); // Include the database connection

$error = ''; // Initialize error message

if (isset($_POST['enter'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Use prepared statements
    $query = $conn->prepare("SELECT adminid, password FROM admin WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            // Regenerate session ID for security
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $row['adminid'];

            // Debugging - check if session is set
            if (!isset($_SESSION['admin_id'])) {
                die("Session not set properly!");
            }

            // Redirect with JavaScript after showing spinner
            echo "<script>
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
                    overlay.style.justifyContent = 'flex-start'; 
                    overlay.style.alignItems = 'center';
                    document.body.appendChild(overlay);

                    // Add loading spinner (at the top of the screen)
                    const loadingSpinner = document.createElement('div');
                    loadingSpinner.style.width = '50px';
                    loadingSpinner.style.height = '50px';
                    loadingSpinner.style.border = '5px solid #f3f3f3';
                    loadingSpinner.style.borderTop = '5px solid #3498db';
                    loadingSpinner.style.borderRadius = '50%';
                    loadingSpinner.style.animation = 'spin 1s linear infinite';
                    loadingSpinner.style.marginTop = '20px';
                    overlay.appendChild(loadingSpinner);

                    // Add logo to the overlay
                    const logoContainer = document.createElement('div');
                    logoContainer.style.flex = '1';
                    logoContainer.style.display = 'flex';
                    logoContainer.style.justifyContent = 'center';
                    logoContainer.style.alignItems = 'center';
                    logoContainer.style.width = '100%';

                    const logo = document.createElement('img');
                    logo.src = '../images/logo.jpg';
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
                        window.location.href = 'admin_home.php';
                    }, 2000);
                });
            </script>";
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sneakers Street</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../images/logo.jpg" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./css/newstyle.css">
    <link rel="stylesheet" href="../css/admlog.css">
    <style>
        .error-message {
            text-align: center;
            color: red;
            margin-top: 1px;
        }

        .success-alert {
            text-align: center;
            color: green;
            margin-top: 10px;
        }

        .spinner {
            display: none;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-top: 4px solid #4285f4;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">
            <img src="../images/logo.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
            Sneakers Street
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Corrected structure: Now includes the navigation links inside the collapsible div -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="../product.php">Product</a></li>
                <li class="nav-item"><a class="nav-link" href="../aboutus.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="../contactus.php">Contact Us</a></li>
                <li class="nav-item"><a class="nav-link" href="../privacy.php">Privacy Policy</a></li>
                <li class="nav-item"><a class="nav-link" href="../faqs.php">FAQs</a></li>
            </ul>
        </div>
    </nav>

    <?php include('../function/admin_login.php'); ?>

    <div id="admin">
        <div class="logo-container">
            <img src="../images/logo.jpg" alt="Logo">
        </div>
        <legend>Admin Login</legend>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="success-alert" id="success-alert" style="display: none;">Login Successful! Redirecting...</div>
        <div class="spinner" id="spinner"></div>

        <form method="post" class="well">
            <div class="input-container">
                <input type="text" name="username" id="username" placeholder=" " required>
                <label for="username">Username</label>
            </div>
            <div class="input-container">
                <input type="password" name="password" id="password" placeholder=" " required>
                <label for="password">Password</label>
            </div>

            <div class="checkbox-container">
                <input type="checkbox" id="show-password" onclick="togglePassword()">
                <label for="show-password">Show Password</label>
            </div>

            <input type="submit" name="enter" value="Login" class="btn btn-primary">
            <br><br>
            <a href="admin_signup.php" class="btn btn-info">Create Account</a>
        </form>
    </div>

    <script>
        function togglePassword() {
            let passwordField = document.getElementById("password");
            let checkbox = document.getElementById("show-password");
            passwordField.type = checkbox.checked ? "text" : "password";
        }

        function showSpinner() {
            const successAlert = document.getElementById('success-alert');
            const spinner = document.getElementById('spinner');
            if (successAlert) successAlert.style.display = 'block';
            if (spinner) spinner.style.display = 'block';

        }

        // Remove error message after 2 seconds
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => {
                let errorMessage = document.querySelector('.error-message');
                if (errorMessage) {
                    errorMessage.style.transition = "opacity 0.5s ease-out";
                    errorMessage.style.opacity = "0"; // Fade out

                    setTimeout(() => {
                        if (errorMessage) {
                            errorMessage.style.display = "none"; // Hide completely
                            errorMessage.remove(); // Remove from DOM
                        }
                    }, 500); // Wait for fade-out to complete before removing
                }
            }, 1500);
        });
    </script>
</body>

</html>
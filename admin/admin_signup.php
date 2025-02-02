<?php
include('../db/dbconn.php'); // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!preg_match("/^[a-zA-Z0-9]{4,}$/", $username)) {
        $error = "Username must be at least 4 characters and contain only letters and numbers.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
        $error = "Password must be at least 8 characters, include uppercase, lowercase, a number, and a special character.";
    } else {
        // Check if username already exists using prepared statement
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $query = $stmt->get_result();

        if ($query->num_rows > 0) {
            $error = "Username already exists.";
        } else {
            // Hash the password before inserting into the database
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                $success = "Account created successfully. Redirecting to login...";
                echo "<script>
                    setTimeout(function() {
                        window.location = 'admin_index.php';
                    }, 1500);
                </script>";
            } else {
                $error = "An error occurred. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Sign Up - Sneakers Street</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../images/logo.jpg">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admsignup.css">
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

    <div id="signup" style="margin: 50px auto; width: 400px;">
        <form method="POST" class="well">
            <center>
                <legend>Create Admin Account</legend>
                <?php
                if (isset($error)) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
                if (isset($success)) {
                    echo "<div class='alert alert-success'>$success</div>";
                    echo "<script>
                        setTimeout(function() {
                            window.location = 'admin_index.php';
                        }, 1000); // 1000 milliseconds = 1 second
                    </script>";
                }
                ?>
                <div class="input-container">
                    <input type="text" name="username" id="username" placeholder=" " required>
                    <label for="username">Username</label>
                </div>
                <div class="input-container">
                    <input type="password" name="password" id="password" placeholder=" " required>
                    <label for="password">Password</label>
                </div>
                <div class="input-container">
                    <input type="password" name="confirm_password" id="confirm_password" placeholder=" " required>
                    <label for="confirm_password">Confirm Password</label>
                </div>
                <input type="submit" value="Sign Up" class="btn btn-primary">
                <!-- "Back to Login" button inside the form -->
                <button type="button" onclick="window.location.href='admin_index.php'" class="btn-back-to-login">
                    Back to Login
                </button>
            </center>
        </form>
    </div>
</body>

</html>
<?php
include("function/session.php");
include("db/dbconn.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneakers Street</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/newstyle.css">
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
                    <a class="nav-link" href="home.php"><i class="icon-user"></i> <?php echo $fetch['firstname']; ?> <?php echo $fetch['lastname']; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="function/logout.php"><i class="icon-off"></i> Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title text-center">Edit My Account</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="function/edit_customer.php">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="firstname">Firstname</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Firstname" required value="<?php echo htmlspecialchars($fetch['firstname']); ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="mi">M.I.</label>
                                    <input type="text" class="form-control" id="mi" name="mi" placeholder="Middle Initial" maxlength="1" required value="<?php echo htmlspecialchars($fetch['mi']); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="lastname">Lastname</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Lastname" required value="<?php echo htmlspecialchars($fetch['lastname']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="Address" required value="<?php echo htmlspecialchars($fetch['address']); ?>">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="country">Province</label>
                                    <input type="text" class="form-control" id="country" name="country" placeholder="Province" required value="<?php echo htmlspecialchars($fetch['country']); ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="zipcode">ZIP Code</label>
                                    <input type="text" class="form-control" id="zipcode" name="zipcode" placeholder="ZIP Code" required value="<?php echo htmlspecialchars($fetch['zipcode']); ?>" maxlength="4">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="mobile">Mobile Number</label>
                                    <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile Number" value="<?php echo htmlspecialchars($fetch['mobile']); ?>" maxlength="11">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="telephone">Telephone Number</label>
                                    <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Telephone Number" value="<?php echo htmlspecialchars($fetch['telephone']); ?>" maxlength="8">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($fetch['email']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password">
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" name="edit" class="btn btn-primary">Save Changes</button>
                                <a href="home.php" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
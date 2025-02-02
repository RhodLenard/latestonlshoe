<?php
include("../function/admin_session.php");
include("../db/dbconn.php");
?>
<!DOCTYPE html>
<html>

<head>
	<title>Sneakers Street</title>
		<meta charset="UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="../images/logo.jpg">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
	<link rel="stylesheet" href="../css/admhome.css">
	<link rel="stylesheet" href="../css/fea.css">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<style>
		/* Ensure the container has proper padding and margin */
		.container {
			padding: 1rem;
			margin-left: 300px;
			margin-top: 5%;
			/* Adjust as necessary for responsiveness */
		}

		/* Responsive card layout */
		.card {
			display: flex;
			flex-direction: column;
			padding: 1rem;
			box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
			border-radius: 8px;
			border: none;
		}

		/* Adjust grid columns for different screen sizes */
		@media (max-width: 1200px) {
			.col-lg-4 {
				flex: 0 0 50%;
				max-width: 50%;
			}
		}

		@media (max-width: 768px) {
			.col-md-6 {
				flex: 0 0 100%;
				max-width: 100%;
			}

			.container {
				margin-left: 0;
				/* Remove margin for smaller screens */
				padding: 1rem;
			}
		}

		@media (max-width: 480px) {
			.container {
				padding: 0.5rem;
			}

			.card {
				padding: 0.75rem;
			}
		}
	</style>
</head>

<body style="display: inline;">
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
		<div class="container-fluid">
			<a class="navbar-brand" href="#">
				<img src="../images/logo.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
				Sneakers Street
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ms-auto">
					<?php
					$id = (int) $_SESSION['admin_id'];
					$query = $conn->query("SELECT * FROM admin WHERE adminid = '$id'") or die(mysqli_error($conn));
					$fetch = $query->fetch_array();
					?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							Welcome, <?php echo isset($fetch['username']) ? $fetch['username'] : 'Guest'; ?>
						</a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
							<li><a class="dropdown-item" href="../function/logout.php">Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>


	<div class="sidebar">
		<ul class="list-unstyled">
			<li><a href="admin_home.php">Dashboard</a></li>
			<li>
				<a href="#productsSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Products</a>
				<ul class="collapse list-unstyled" id="productsSubmenu">
					<li><a href="admin_feature.php">Features</a></li>
					<li><a href="admin_product.php">Basketball</a></li>
					<li><a href="admin_football.php">Sneakers</a></li>
					<li><a href="admin_running.php">Running</a></li>
				</ul>
			</li>
			<li><a href="transaction.php">Transactions</a></li>
			<li><a href="customer.php">Customers</a></li>
			<li><a href="message.php">Messages</a></li>
			<li><a href="order.php">Orders</a></li>
		</ul>
	</div>

	<div class="container py-4">
		<div class="alert alert-info text-center">
			<h2>Customers</h2>
		</div>
		<div class="mb-3">
			<input type="text" class="form-control" placeholder="Search Customers here..." id="filter">
		</div>

		<div class="row">
			<?php
			$query = $conn->query("SELECT * FROM `customer`") or die(mysqli_error());
			while ($fetch = $query->fetch_array()) {
			?>
				<div class="col-12 col-md-6 col-lg-4 mb-3">
					<div class="card border-0 shadow-sm p-3 d-flex flex-column">
						<h5 class="card-title mb-2">
							<?php echo $fetch['firstname'] . " " . $fetch['mi'] . " " . $fetch['lastname']; ?>
						</h5>
						<p class="mb-1"><strong>Address:</strong> <?php echo $fetch['address']; ?></p>
						<p class="mb-1"><strong>Province:</strong> <?php echo $fetch['country']; ?></p>
						<p class="mb-1"><strong>Zipcode:</strong> <?php echo $fetch['zipcode']; ?></p>
						<p class="mb-1"><strong>Mobile:</strong> <?php echo $fetch['mobile']; ?></p>
						<p class="mb-1"><strong>Telephone:</strong> <?php echo $fetch['telephone']; ?></p>
						<p class="mb-0"><strong>Email:</strong> <?php echo $fetch['email']; ?></p>
					</div>
				</div>
			<?php
			}
			?>
		</div>
	</div>





</body>

</html>
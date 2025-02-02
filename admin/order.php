<?php
include("../function/admin_session.php");
include("../db/dbconn.php");
?>
<!DOCTYPE html>
<html>

<head>
	<title>Sneakers Street</title>
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
		<div class="alert alert-info">
			<center>
				<h2>Orders</h2>
			</center>
		</div>
		<br />
		<div style='width:975px;' class="alert alert-info">
			<table class="table table-hover">
				<thead>
					<tr>
						<th style="pointer-events: none;">SHOE</th>
						<th style="pointer-events: none;">Transaction No.</th>
						<th style="pointer-events: none;">AMOUNT</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$Q1 = $conn->query("SELECT * FROM transaction WHERE order_stat = 'Confirmed'");
					while ($r1 = $Q1->fetch_array()) {

						$tid = $r1['transaction_id'];

						$Q2 = $conn->query("SELECT * FROM transaction_detail LEFT JOIN product ON product.product_id = transaction_detail.product_id WHERE transaction_detail.transaction_id = '$tid' ");
						$r2 = $Q2->fetch_array();

						$pid = $r2['product_id'];
						$o_qty = $r2['order_qty'];

						$p_price = $r2['product_price'];
						$brand = $r2['product_name'];

						echo "<tr>";
						echo "<td>" . $brand . "</td>";
						echo "<td>" . $tid . "</td>";
						echo "<td>" . formatMoney($p_price) . "</td>";
						echo "</tr>";
					}

					$Q3 = $conn->query("SELECT sum(amount) FROM transaction WHERE order_stat = 'Confirmed'");
					while ($r3 = $Q3->fetch_array()) {

						$amnt = $r3['sum(amount)'];
						echo "<tr><td></td><td>TOTAL : </td> <td><b>Php " . formatMoney($amnt) . "</b></td></tr>";
					}
					?>
				</tbody>
			</table>
		</div>

		<?php
		function formatMoney($number, $fractional = false)
		{
			if ($fractional) {
				$number = sprintf('%.2f', $number);
			}
			while (true) {
				$replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
				if ($replaced != $number) {
					$number = $replaced;
				} else {
					break;
				}
			}
			return $number;
		}
		?>





	</div>
	</form>
	</div>
	</div>



</body>

</html>
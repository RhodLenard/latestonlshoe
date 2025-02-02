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
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
	</script>
</head>

<body>
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

	<div class="main-content" style="padding: 60px 20px;">
		<div class="alert alert-info text-center">
			<h2>Customers</h2>
		</div>
		<div class="mb-3">
			<input type="text" class="form-control" placeholder="Search Customers here..." id="filter">
		</div>

		<div class="alert alert-info">
			<table class="table table-hover" style="background-color:;">
				<thead>
					<tr style="font-size:16px;">
						<th style="pointer-events: none;">ID</th>
						<th style="pointer-events: none;">DATE</th>
						<th style="pointer-events: none;">Customer Name</th>
						<th style="pointer-events: none;">Total Amount</th>
						<th style="pointer-events: none;">Order Status</th>
						<th style="pointer-events: none;">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php

					$query = $conn->query("SELECT * FROM transaction LEFT JOIN customer ON customer.customerid = transaction.customerid") or die(mysqli_error());
					while ($fetch = $query->fetch_array()) {
						$id = $fetch['transaction_id'];
						$amnt = $fetch['amount'];
						$o_stat = $fetch['order_stat'];
						$o_date = $fetch['order_date'];

						$name = $fetch['firstname'] . ' ' . $fetch['lastname'];
					?>
						<tr>
							<td><?php echo $id; ?></td>
							<td><?php echo $o_date; ?></td>
							<td><?php echo $name; ?></td>
							<td><?php echo $amnt; ?></td>
							<td><?php echo $o_stat; ?></td>
							<td>
								<a href="receipt.php?tid=<?php echo $id; ?>">View</a>
								<?php if ($o_stat == 'Paid'): ?>
									| <a class="btn btn-mini btn-info"
										href="confirm.php?id=<?= $id ?>&action=confirm">
										Confirm
									</a>
									| <a class="btn btn-mini btn-danger"
										href="confirm.php?id=<?= $id ?>&action=cancel">
										Cancel
									</a>
								<?php endif; ?>
							</td>
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>

	<?php
	/* stock in */
	if (isset($_POST['stockin'])) {

		$pid = $_POST['pid'];

		$result = $conn->query("SELECT * FROM `stock` WHERE product_id='$pid'") or die(mysqli_error());
		$row = $result->fetch_array();

		$old_stck = $row['qty'];
		$new_stck = $_POST['new_stck'];
		$total = $old_stck + $new_stck;

		$que = $conn->query("UPDATE `stock` SET `qty` = '$total' WHERE `product_id`='$pid'") or die(mysqli_error());

		header("Location:admin_product.php");
	}

	/* stock out */
	if (isset($_POST['stockout'])) {

		$pid = $_POST['pid'];

		$result = $conn->query("SELECT * FROM `stock` WHERE product_id='$pid'") or die(mysqli_error());
		$row = $result->fetch_array();

		$old_stck = $row['qty'];
		$new_stck = $_POST['new_stck'];
		$total = $old_stck - $new_stck;

		$que = $conn->query("UPDATE `stock` SET `qty` = '$total' WHERE `product_id`='$pid'") or die(mysqli_error());

		header("Location:admin_product.php");
	}
	?>

</body>

</html>
<script type="text/javascript">
	$(document).ready(function() {

		$('.remove').click(function() {

			var id = $(this).attr("id");


			if (confirm("Are you sure you want to delete this product?")) {


				$.ajax({
					type: "POST",
					url: "../function/remove.php",
					data: ({
						id: id
					}),
					cache: false,
					success: function(html) {
						$(".del" + id).fadeOut(2000, function() {
							$(this).remove();
						});
					}
				});
			} else {
				return false;
			}
		});
	});
</script>
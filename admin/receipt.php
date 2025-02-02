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


	<!--Le Facebox-->
	<link href="../facefiles/facebox.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="../facefiles/jquery-1.9.js" type="text/javascript"></script>
	<script src="../facefiles/jquery-1.2.2.pack.js" type="text/javascript"></script>
	<script src="../facefiles/facebox.js" type="text/javascript"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('a[rel*=facebox]').facebox()
		})
	</script>

	<script language="javascript" type="text/javascript">
		function printDiv(divID) {
			//Get the HTML of div
			var divElements = document.getElementById(divID).innerHTML;
			//Get the HTML of whole page
			var oldPage = document.body.innerHTML;

			//Reset the page's HTML with div's HTML only
			document.body.innerHTML =
				"<html><head><title></title></head><body>" +
				divElements + "</body>";

			//Print Page
			window.print();

			//Restore original HTML
			document.body.innerHTML = oldPage;
		}
	</script>
	<style>
		form.well {
			width: 50%;
			/* Fixed width for the form */
			padding: 30px;
			/* Padding for spacing */
			background-color: #ffffff;
			/* White background */
			border-radius: 10px;
			/* Rounded corners */
			box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
			/* Soft shadow */
			border: 1px solid #e0e0e0;
			/* Light border for subtle definition */
			text-align: center;
			/* Center-align text */
			display: flex;
			/* Add this */
			flex-direction: column;
			/* Add this */
			align-items: center;
			/* Add this */
			justify-content: center;
			/* Add this */
			box-sizing: border-box;
			/* Add this */
		}

		/* Legend (Adminstrator text) */
		form.well legend {
			font-size: 24px;
			/* Larger font size */
			font-weight: bold;
			/* Bold text */
			color: #333;
			/* Dark gray color */
			margin-bottom: 20px;
			/* Spacing below the legend */
		}
	</style>
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

	<div style="display: flex; justify-content:center;">
		<div class="main-content" style="padding: 12rem 20px;">
			<div class="alert alert-info">
				<center>
					<h2>Transactions</h2>
				</center>
			</div>
			<br />

			<div class="alert alert-info">
				<form method="post" class="well" style="background-color:#fff; overflow:hidden; width:100%">
					<div id="printablediv">
						<center>
							<table class="table" style="width:50%;">
								<label style="font-size:25px; display:block">Sneakers Street</label>
								<label style="font-size:20px; display:block">Official Receipt</label>
								<tr>
									<th>
										<h5>Quantity</h5>
									</th>
									<th>
										<h5>Product Name</h5>
									</th>
									<th>
										<h5>Size</h5>
									</th>
									<th>
										<h5>Price</h5>
									</th>
								</tr>

								<?php
								$t_id = $_GET['tid'];

								// Fetch transaction data
								$query = $conn->query("SELECT * FROM transaction WHERE transaction_id = '$t_id'") or die(mysqli_error($conn));
								$fetch = $query->fetch_array();

								$amnt = $fetch['amount'];
								echo "Date: " . $fetch['order_date'] . "<br>";

								// Fetch transaction details with selected size
								$query2 = $conn->query("
			SELECT td.quantity, p.product_name, td.product_size, p.product_price
			FROM transaction_detail td
			LEFT JOIN product p ON td.product_id = p.product_id
			WHERE td.transaction_id = '$t_id'
		") or die(mysqli_error($conn));

								// Display transaction details
								while ($row = $query2->fetch_array()) {
									$oqty = $row['quantity'];
									$pname = $row['product_name'];
									$psize = $row['product_size']; // Correct size from transaction_detail
									$pprice = $row['product_price'];

									echo "<tr>
					<td>$oqty</td>
					<td>$pname</td>
					<td>$psize</td>
					<td>$pprice</td>
				  </tr>";
								}
								?>

							</table>
							<legend></legend>
							<h4>TOTAL: Php <?php echo $amnt; ?></h4>
						</center>
					</div>

					<div class='pull-right'>
						<div class="add"><a onclick="javascript:printDiv('printablediv')" name="print" style="cursor:pointer;" class="btn btn-info"><i class="icon-white icon-print"></i> Print Receipt</a></div>
					</div>
				</form>
			</div>
		</div>
	</div>

</body>

</html>
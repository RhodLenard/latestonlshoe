<?php
include("../function/admin_session.php");
include("../db/dbconn.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sneakers Street</title>
	<link rel="icon" href="../images/logo.jpg">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
	<link rel="stylesheet" href="../css/admhome.css">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>


	<script type="text/javascript">
		$(document).ready(function() {
			Highcharts.chart('container', {
				chart: {
					type: 'bar'
				},
				title: {
					text: 'Total Quantity per Brand - <?php echo date("Y"); ?>'
				},
				xAxis: {
					categories: [
						<?php
						$brands_result = $conn->query("
                        SELECT 
                            p.brand AS brand,
                            SUM(s.qty) AS total_qty
                        FROM 
                            product p
                        JOIN 
                            stock s
                        ON 
                            p.product_id = s.product_id
                        GROUP BY 
                            p.brand
                        ORDER BY 
                            p.brand
                    ");
						while ($row = $brands_result->fetch_assoc()) {
							echo "'" . $row['brand'] . "',";
						}
						?>
					],
					title: {
						text: 'Brands'
					}
				},
				yAxis: {
					min: 0,
					title: {
						text: 'Total Quantity'
					},
					allowDecimals: false
				},
				tooltip: {
					shared: true,
					pointFormat: '{series.name}: <b>{point.y}</b>'
				},
				plotOptions: {
					series: {
						cursor: 'pointer',
						point: {
							events: {
								click: function() {
									const brand = this.category;
									window.location.href = 'brand_details.php?brand=' + encodeURIComponent(brand);
								}
							}
						}
					}
				},
				series: [{
					name: 'Total Quantity',
					data: [
						<?php
						$brands_result->data_seek(0); // Reset pointer for looping again
						while ($row = $brands_result->fetch_assoc()) {
							echo $row['total_qty'] . ",";
						}
						?>
					]
				}],
				credits: {
					enabled: false
				}
			});
		});
	</script>
</head>

<body>
	<!-- Navigation Bar -->
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

	<!-- Sidebar -->
	<div class="sidebar">
		<ul class="list-unstyled">
			<li><a href="admin_home.php">Dashboard</a></li>
			<li>
				<a href="#productsSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Products</a>
				<ul class="collapse list-unstyled" id="productsSubmenu">
					<li><a href="admin_feature.php" style="margin-left:15px;">Features</a></li>
					<li><a href="admin_product.php" style="margin-left:15px;">Basketball</a></li>
					<li><a href="admin_football.php" style="margin-left:15px;">Sneakers</a></li>
					<li><a href="admin_running.php" style="margin-left:15px;">Running</a></li>
				</ul>
			</li>
			<li><a href="transaction.php">Transactions</a></li>
			<li><a href="customer.php">Customers</a></li>
			<li><a href="message.php">Messages</a></li>
			<li><a href="order.php">Orders</a></li>
		</ul>
	</div>



	<!-- Main Content -->
	<div class="main-content" style="padding: 60px 20px;">
		<div id="container" style="height: 600px; width: 100%;"></div>
	</div>
</body>

</html>
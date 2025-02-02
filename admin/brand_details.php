<?php
include("../db/dbconn.php");

// Check if the brand is passed as a query parameter
if (isset($_GET['brand'])) {
  $brand = $conn->real_escape_string($_GET['brand']);

  // Fetch product names, sizes, and total quantities for the selected brand
  $result = $conn->query("
        SELECT 
            p.product_name AS product_name, 
            s.product_size AS size, 
            SUM(s.qty) AS total_qty
        FROM 
            product p
        JOIN 
            stock s
        ON 
            p.product_id = s.product_id
        WHERE 
            p.brand = '$brand'
        GROUP BY 
            p.product_name, s.product_size
        ORDER BY 
            p.product_name, s.product_size
    ");

  $sizes = [];
  while ($row = $result->fetch_assoc()) {
    $sizes[] = $row;
  }
} else {
  die("Brand not specified.");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <title>Brand Details</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
  <div class="container mt-5">
    <h1>Brand: <?php echo htmlspecialchars($brand); ?></h1>
    <table class="table table-bordered mt-4">
      <thead>
        <tr>
          <th>Product Name</th>
          <th>Size</th>
          <th>Total Quantity</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($sizes)) { ?>
          <?php foreach ($sizes as $size) { ?>
            <tr>
              <td><?php echo htmlspecialchars($size['product_name']); ?></td>
              <td><?php echo htmlspecialchars($size['size']); ?></td>
              <td><?php echo htmlspecialchars($size['total_qty']); ?></td>
            </tr>
          <?php } ?>
        <?php } else { ?>
          <tr>
            <td colspan="3">No sizes available for this brand.</td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <a href="javascript:history.back()" class="btn btn-secondary">Back</a>
  </div>
</body>

</html>
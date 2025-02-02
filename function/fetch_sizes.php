<?php
include("../db/dbconn.php");

if (isset($_POST['brand'])) {
  $brand = $conn->real_escape_string($_POST['brand']);

  $result = $conn->query("
        SELECT TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(product_size, ',', numbers.n), ',', -1)) AS size
        FROM product
        JOIN (
            SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL 
            SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL 
            SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10
        ) numbers
        ON CHAR_LENGTH(product_size) - CHAR_LENGTH(REPLACE(product_size, ',', '')) >= numbers.n - 1
        WHERE brand = '$brand'
    ");

  $sizes = [];
  while ($row = $result->fetch_assoc()) {
    $sizes[] = $row['size'];
  }

  // Debug output
  if (!empty($sizes)) {
    echo "Available sizes: " . implode(', ', $sizes); // Return sizes
  } else {
    echo "No sizes found for this brand.";
  }
}

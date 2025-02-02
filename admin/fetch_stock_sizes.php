<?php
include("../db/dbconn.php");

$id = $_GET['id'];

// Fetch sizes and stock for the given product ID
$result = $conn->query("SELECT * FROM stock WHERE product_id = '$id'") or die(mysqli_error($conn));

$sizes = [];
while ($row = $result->fetch_assoc()) {
  $sizes[] = $row;
}

// Sorting function to order sizes numerically
usort($sizes, function ($a, $b) {
  preg_match('/(\d+(?:\.\d+)?)/', $a['product_size'], $a_matches);
  preg_match('/(\d+(?:\.\d+)?)/', $b['product_size'], $b_matches);
  return $a_matches[1] <=> $b_matches[1];
});

// Display sorted sizes
if (!empty($sizes)) {
  foreach ($sizes as $row) {
    echo "
            <div class='row align-items-center mb-3'>
                <div class='col-6'>
                    <span class='fw-bold' style='font-size: 1rem; color: #333;'>{$row['product_size']}</span>
                    <span class='text-muted d-block' style='font-size: 0.85rem;'>Available: {$row['qty']}</span>
                </div>
                <div class='col-6'>
                    <input type='number' name='size[{$row['product_size']}]' class='form-control text-center' min='1' max='{$row['qty']}' placeholder='Qty to Remove'>
                </div>
            </div>";
  }
} else {
  echo "<p class='text-muted'>No stock available for this product.</p>";
}

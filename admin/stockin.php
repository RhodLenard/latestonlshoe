<?php
include("../db/dbconn.php");

// Process form submission
if (isset($_POST['stockin'])) {
    $pid = $_POST['pid']; // Product ID
    $selected_sizes = $_POST['product_size'] ?? []; // Selected sizes
    $quantities = []; // Quantities for selected sizes

    // Loop through selected sizes and get their quantities
    foreach ($selected_sizes as $size) {
        $sanitized_size = str_replace([' ', '.'], '_', $size); // Sanitize size for the quantity key
        $qty_key = 'qty_' . $sanitized_size; // e.g., qty_US_7_5
        $quantities[$size] = isset($_POST[$qty_key]) ? (int)$_POST[$qty_key] : 0; // Get quantity
    }

    // Update stock for selected sizes
    foreach ($quantities as $size => $qty) {
        if ($qty > 0) {
            // Fetch the current stock for the selected size
            $result = $conn->query("SELECT qty FROM stock WHERE product_id='$pid' AND product_size='$size'") or die(mysqli_error());
            if ($result->num_rows > 0) {
                $row = $result->fetch_array();
                $old_stck = (int)$row['qty']; // Current stock for the selected size
                $total = $old_stck + $qty; // Calculate new stock

                // Update the stock for the selected size
                $conn->query("UPDATE stock SET qty = '$total' WHERE product_id='$pid' AND product_size='$size'") or die(mysqli_error());
            } else {
                // If no stock entry exists for the selected size, insert a new row
                $conn->query("INSERT INTO stock (product_id, product_size, qty) VALUES ('$pid', '$size', '$qty')") or die(mysqli_error());
            }
        }
    }

    // Redirect back to the product page
    echo "<script>window.location = 'admin_product.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Stock In</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="all">
    <style>
        /* Style for size container */
        .size-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 10px;
        }

        /* Style for each size group */
        .size-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            /* Center align items */
            gap: 5px;
            /* Space between size button and quantity input */
        }

        /* Style for size buttons */
        .size-button {
            border: 2px solid #ccc;
            padding: 10px 15px;
            text-align: center;
            background: #f9f9f9;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s, color 0.3s, border-color 0.3s;
            min-width: 60px;
            /* Fixed width for uniformity */
        }

        /* Hover effect */
        .size-button:hover {
            background: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        /* Selected effect */
        input[type="checkbox"]:checked+.size-button {
            background: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        /* Quantity input style */
        .quantity-input {
            width: 50px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Add Stock</h2>
    <form method="post" action="">
        <input type="hidden" name="pid" value="<?php echo $_GET['id']; ?>">

        <div class="size-container">
            <?php
            $sizes = ["US 7", "US 7.5", "US 8", "US 8.5", "US 9", "US 9.5", "US 10", "US 10.5", "US 11", "US 11.5", "US 12"];
            foreach ($sizes as $size) {
                $sanitized_size = str_replace([' ', '.'], '_', $size); // Replace spaces and dots with underscores
                echo "
                <div class='size-group'>
                    <label style='cursor: pointer;'>
                        <input type='checkbox' name='product_size[]' value='$size' style='display: none;'>
                        <div class='size-button'>$size</div>
                    </label>
                    <input type='number' name='qty_$sanitized_size' placeholder='Qty' class='quantity-input'>
                </div>";
            }
            ?>
        </div>

        <button type="submit" name="stockin">Add Stock</button>
    </form>
</body>

</html>
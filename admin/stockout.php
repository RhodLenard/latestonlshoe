<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Out</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .stock-out-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: #343a40;
        }

        .size-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
        }

        .size-row span {
            font-weight: 500;
        }

        .quantity-input {
            width: 100px;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            text-align: center;
        }

        .btn-submit {
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="stock-out-container">
        <?php
        include("../db/dbconn.php");
        $id = $_GET['id'];

        // Fetch sizes and stock for the given product ID
        $result = $conn->query("SELECT * FROM stock WHERE product_id = '$id'") or die(mysqli_error($conn));

        if ($result->num_rows > 0) {
            $sizes = [];
            while ($row = $result->fetch_assoc()) {
                $sizes[] = $row;
            }
        } else {
            $sizes = [];
        }
        ?>

        <h2 class="form-title">Stock Out</h2>
        <form method="post">
            <input type="hidden" name="pid" value="<?php echo $id; ?>" />

            <?php if (!empty($sizes)) : ?>
                <?php foreach ($sizes as $size) : ?>
                    <div class="size-row">
                        <span>Size: <b><?php echo $size['product_size']; ?></b></span>
                        <span>Available Stock: <b><?php echo $size['qty']; ?></b></span>
                        <input type="number" name="size[<?php echo $size['product_size']; ?>]" class="quantity-input" min="0" max="<?php echo $size['qty']; ?>" placeholder="Qty to Remove">
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="alert alert-warning" role="alert">
                    No stock available for this product.
                </div>
            <?php endif; ?>

            <button type="submit" name="stockout" class="btn btn-primary btn-submit">
                <i class="bi bi-box-arrow-up"></i> Save Data
            </button>
        </form>
    </div>
</body>

</html>
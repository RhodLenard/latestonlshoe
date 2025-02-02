<?php
include("../function/admin_session.php");
include("../db/dbconn.php");

// Form Processing Logic
if (isset($_POST['add'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $brand = $_POST['brand'];
    $category = $_POST['category'];
    $product_size = isset($_POST['product_size']) ? implode(",", $_POST['product_size']) : '';
    $code = rand(0, 98987787866533499);

    $name = $code . $_FILES["product_image"]["name"];
    $type = $_FILES["product_image"]["type"];
    $size = $_FILES["product_image"]["size"];
    $temp = $_FILES["product_image"]["tmp_name"];
    $error = $_FILES["product_image"]["error"];

    if ($error > 0) {
        die("Error uploading file! Code $error.");
    } elseif ($size > 30000000000) {
        die("Format is not allowed or file size is too big!");
    } else {
        move_uploaded_file($temp, "../photo/" . $name);

        $product_code = rand(0, 999999999);
        $check_query = $conn->query("SELECT * FROM product WHERE product_id = '$product_code'");
        if ($check_query->num_rows > 0) {
            die("Duplicate product_id generated. Please try again.");
        }

        $conn->query("INSERT INTO product (product_id, product_name, product_price, product_image, brand, category, product_size)
                      VALUES ('$product_code', '$product_name', '$product_price', '$name', '$brand', '$category', '$product_size')");

        $sizes = ["US 7", "US 7.5", "US 8", "US 8.5", "US 9", "US 9.5", "US 10", "US 10.5", "US 11", "US 11.5", "US 12"];
        foreach ($sizes as $size) {
            if (isset($_POST['product_size']) && in_array($size, $_POST['product_size'])) {
                $sanitized_size = str_replace([' ', '.'], '_', $size);
                $qty_key = 'qty_' . $sanitized_size;
                $qty = isset($_POST[$qty_key]) ? (int)$_POST[$qty_key] : 0;
                $conn->query("INSERT INTO stock (product_id, product_size, qty) VALUES ('$product_code', '$size', '$qty')")
                    or die("Error inserting stock: " . mysqli_error($conn));
            }
        }
        header("Location: admin_product.php");
        exit();
    }
}

// Stock in
if (isset($_POST['stockin'])) {
    $pid = $_POST['pid'];
    $quantities = [];

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'qty_') === 0 && (int)$value > 0) {
            $size = substr($key, 4); // Remove 'qty_' prefix

            // Replace the first underscore with a space, keeping decimals intact
            $size = preg_replace('/_/', ' ', $size, 1);

            // Ensure numbers remain correct (US 9, US 9.5)
            $size = str_replace('_', '.', $size); // Only convert remaining underscores to dots for decimals

            $quantities[$size] = (int)$value;
        }
    }


    foreach ($quantities as $size => $qty) {
        $result = $conn->query("SELECT qty FROM stock WHERE product_id='$pid' AND product_size='$size'");
        if ($result->num_rows > 0) {
            $row = $result->fetch_array();
            $new_qty = (int)$row['qty'] + $qty;
            $conn->query("UPDATE stock SET qty = '$new_qty' WHERE product_id='$pid' AND product_size='$size'")
                or die(mysqli_error($conn));
        } else {
            $conn->query("INSERT INTO stock (product_id, product_size, qty) VALUES ('$pid', '$size', '$qty')")
                or die(mysqli_error($conn));
        }
    }

    $all_sizes_query = $conn->query("SELECT DISTINCT product_size FROM stock WHERE product_id='$pid'") or die(mysqli_error($conn));
    $all_sizes = [];
    while ($row = $all_sizes_query->fetch_assoc()) {
        $all_sizes[] = $row['product_size'];
    }

    usort($all_sizes, function ($a, $b) {
        preg_match('/(\d+(?:\.\d+)?)/', $a, $a_matches);
        preg_match('/(\d+(?:\.\d+)?)/', $b, $b_matches);
        return $a_matches[1] <=> $b_matches[1];
    });

    $sizes_string = implode(',', $all_sizes);
    $conn->query("UPDATE product SET product_size = '$sizes_string' WHERE product_id = '$pid'")
        or die(mysqli_error($conn));

    echo "<script>alert('Stock added successfully and sizes updated!'); window.location = 'admin_product.php';</script>";
    exit();
}

// Stock out
if (isset($_POST['stockout'])) {
    $pid = $_POST['pid'];
    $sizes_to_remove = $_POST['size'];

    foreach ($sizes_to_remove as $size => $qty_to_remove) {
        $size = $conn->real_escape_string($size);
        $qty_to_remove = (int)$qty_to_remove;

        if ($qty_to_remove > 0) {
            $result = $conn->query("SELECT qty FROM stock WHERE product_id='$pid' AND product_size='$size'") or die(mysqli_error($conn));
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $new_stock = (int)$row['qty'] - $qty_to_remove;

                if ($new_stock > 0) {
                    $conn->query("UPDATE stock SET qty = '$new_stock' WHERE product_id='$pid' AND product_size='$size'")
                        or die(mysqli_error($conn));
                } else {
                    $conn->query("DELETE FROM stock WHERE product_id='$pid' AND product_size='$size'")
                        or die(mysqli_error($conn));
                }
            } else {
                echo "<script>alert('Error: Size $size not found in stock.');</script>";
            }
        }
    }

    echo "<script>alert('Stock updated successfully!'); window.location = 'admin_product.php';</script>";
    exit();
}

// Delete Logic
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $conn->query("DELETE FROM stock WHERE product_id = '$delete_id'") or die(mysqli_error($conn));
    $conn->query("DELETE FROM product WHERE product_id = '$delete_id'") or die(mysqli_error($conn));

    echo "<script>alert('Product deleted successfully!'); window.location = 'admin_product.php';</script>";
    exit();
}
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

    <!-- Add Product Button -->


    <!-- Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="productImage" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="productImage" name="product_image" required>
                        </div>

                        <?php include("random_id.php"); ?>
                        <input type="hidden" name="product_code" value="<?php echo $code; ?>">

                        <div class="mb-3">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="product_name" placeholder="Enter product name" required>
                        </div>

                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Product Price</label>
                            <input type="text" class="form-control" id="productPrice" name="product_price" placeholder="Enter price" required>
                        </div>

                        <div class="mb-3">
                            <h6 class="form-label">Select Available Sizes:</h6>
                            <div class="size-container d-flex flex-wrap gap-2">
                                <?php
                                $sizes = ["US 7", "US 7.5", "US 8", "US 8.5", "US 9", "US 9.5", "US 10", "US 10.5", "US 11", "US 11.5", "US 12"];
                                foreach ($sizes as $size) {
                                    $sanitized_size = str_replace([' ', '.'], '_', $size);
                                    echo "
                                <div class='size-group d-flex flex-column align-items-center'>
                                    <label class='form-check-label'>
                                        <input type='checkbox' class='form-check-input' name='product_size[]' value='$size'>
                                        <span class='badge bg-secondary'>$size</span>
                                    </label>
                                    <input type='number' class='form-control mt-1' name='qty_$sanitized_size' placeholder='Qty' min='1' style='width: 70px;'>
                                </div>";
                                }
                                ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="brandName" class="form-label">Brand Name</label>
                            <input type="text" class="form-control" id="brandName" name="brand" placeholder="Enter brand name" required>
                        </div>

                        <input type="hidden" name="category" value="basketball">

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="add">Add</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>


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

    <div class="main-content" style="padding: 60px 20px;">
        <!-- Header Section -->
        <div class="alert text-center mb-4" style="border-bottom: 2px solid #ccc;">
            <h2 style="font-size: 1.5rem; font-weight: bold;">Basketball</h2>
        </div>

        <!-- Add Product and Search Bar Section -->
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <a href="#addProductModal" role="button" class="btn custom-btn w-100" data-bs-toggle="modal">
                    <i class="bi bi-plus-circle"></i> Add Product
                </a>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" name="filter" placeholder="Search Product here..." id="filter">
            </div>
        </div>

        <!-- Product Table Section -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr class="text-center" style="white-space: nowrap;">
                        <th class="text-wrap text-truncate" style="max-width: 120px;">Product Image</th>
                        <th class="text-wrap text-truncate" style="max-width: 150px;">Product Name</th>
                        <th class="text-wrap text-truncate" style="max-width: 120px;">Product Price</th>
                        <th class="text-wrap text-truncate" style="max-width: 150px;">Product Sizes</th>
                        <th class="text-wrap text-truncate" style="max-width: 120px;">No. of Stock</th>
                        <th class="text-wrap text-truncate" style="max-width: 180px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = $conn->query("SELECT * FROM `product` WHERE category='basketball' ORDER BY product_id DESC") or die(mysqli_error());
                    while ($fetch = $query->fetch_array()) {
                        $id = $fetch['product_id'];
                    ?>
                        <tr class="text-center">
                            <td>
                                <img src="../photo/<?php echo $fetch['product_image'] ?>"
                                    class="img-fluid rounded" style="height: 70px; width: 80px; cursor: pointer;"
                                    data-bs-toggle="modal" data-bs-target="#imageModal"
                                    onclick="enlargeImage('../photo/<?php echo $fetch['product_image'] ?>')">
                            </td>
                            <td><?php echo $fetch['product_name'] ?></td>
                            <td><?php echo $fetch['product_price'] ?></td>
                            <td><?php echo $fetch['product_size'] ?></td>
                            <?php
                            $query1 = $conn->query("SELECT SUM(qty) AS total_qty FROM `stock` WHERE product_id='$id'") or die(mysqli_error());
                            $fetch1 = $query1->fetch_array();
                            $qty = $fetch1['total_qty'] ?? 0;
                            ?>
                            <td><?php echo $qty; ?></td>
                            <td>
                                <div class="d-flex flex-wrap justify-content-center gap-1">
                                    <button class="btn btn-success btn-sm stockin-btn" data-bs-toggle="modal" data-bs-target="#stockInModal" data-id="<?php echo $id; ?>">
                                        <i class="bi bi-plus"></i> Stock In
                                    </button>
                                    <button class="btn btn-warning btn-sm stockout-btn" data-bs-toggle="modal" data-bs-target="#stockOutModal" data-id="<?php echo $id; ?>">
                                        <i class="bi bi-box-arrow-up"></i> Stock Out
                                    </button>
                                    <a href='admin_product.php?delete_id=<?php echo $id; ?>' class='btn btn-danger btn-sm' onclick="return confirm('Are you sure you want to delete this product?');">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Product Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="modalImage" class="img-fluid rounded" style="max-width: 100%;">
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript for Enlarging Image -->
        <script>
            function enlargeImage(imageSrc) {
                document.getElementById("modalImage").src = imageSrc;
            }
        </script>




        <!-- Stock In Modal -->
        <div class="modal fade" id="stockInModal" tabindex="-1" aria-labelledby="stockInModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-plus-circle"></i> Add Stock
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="">
                            <input type="hidden" id="stockin_pid" name="pid">
                            <p class="text-muted mb-4">Fill in the quantities for each size to update the stock inventory.</p>
                            <div class="row g-3">
                                <?php
                                $sizes = ["US 7", "US 7.5", "US 8", "US 8.5", "US 9", "US 9.5", "US 10", "US 10.5", "US 11", "US 11.5", "US 12"];
                                foreach ($sizes as $size) {
                                    $sanitized_size = str_replace([' ', '.'], '_', $size);
                                    echo "
                            <div class='col-md-4'>
                                <div class='card border-light shadow-sm'>
                                    <div class='card-body text-center'>
                                        <h6 class='fw-bold'>$size</h6>
                                        <input type='number' name='qty_$sanitized_size' placeholder='Enter Qty' class='form-control text-center mt-2' min='1'>
                                    </div>
                                </div>
                            </div>";
                                }
                                ?>
                            </div>
                            <button type="submit" name="stockin" class="btn btn-success w-100 mt-4">
                                <i class="bi bi-box-arrow-in-down"></i> Add Stock
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="stockOutModal" tabindex="-1" aria-labelledby="stockOutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Remove Stock</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="admin_product.php">
                            <input type="hidden" id="stockout_pid" name="pid">

                            <p class="text-muted">Select the sizes and enter the quantities you want to remove from the inventory.</p>
                            <div id="stockOutSizes">
                                <!-- Sorted sizes will be dynamically loaded here -->
                            </div>

                            <button type="submit" name="stockout" class="btn btn-danger w-100 mt-4">
                                Confirm Stock Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".stockin-btn").forEach(button => {
                    button.addEventListener("click", function() {
                        let productId = this.getAttribute("data-id");
                        document.getElementById("stockin_pid").value = productId;
                    });
                });
            });

            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll('.stockout-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const productId = this.getAttribute('data-id'); // Get product ID
                        document.getElementById('stockout_pid').value = productId; // Set hidden input value

                        // Fetch sorted sizes and populate the modal
                        fetch(`fetch_stock_sizes.php?id=${productId}`)
                            .then(response => response.text())
                            .then(data => {
                                document.getElementById('stockOutSizes').innerHTML = data; // Populate sizes
                            })
                            .catch(error => console.error('Error fetching stock sizes:', error));
                    });
                });
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Select the search input field
                const searchInput = document.getElementById("filter");

                // Add event listener for keyup event
                searchInput.addEventListener("keyup", function() {
                    const searchValue = searchInput.value.toLowerCase();
                    const tableRows = document.querySelectorAll("tbody tr");

                    tableRows.forEach(row => {
                        const productName = row.querySelector("td:nth-child(2)").textContent.toLowerCase();

                        if (productName.includes(searchValue)) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                    });
                });
            });
        </script>


</body>

</html>
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['action']) && $_GET['action'] == "add") {
    $product_id = intval($_GET['id']); // Ensure the ID is an integer
    $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + 1;
}

$total = 0;
$product_ids = array_keys($_SESSION['cart']); // Collect product IDs

if (!empty($product_ids)) {
    // Make sure product IDs are sanitized and formatted correctly
    $product_ids = array_map('intval', $product_ids);
    $product_ids_list = implode(',', $product_ids); // Create a comma-separated list

    $query = "SELECT * FROM products WHERE id IN ($product_ids_list)";
    
    // Execute the query and check for errors
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $products = [];
    while ($product = mysqli_fetch_assoc($result)) {
        $products[$product['id']] = $product;
    }

    foreach ($_SESSION['cart'] as $id => $quantity) {
        if (isset($products[$id])) {
            $total += $products[$id]['price'] * $quantity;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Shopping Cart</title>
    <style>

body {
    font-family: Arial, sans-serif;
    background-color: #f7f7f7; /* Soft gray */
}

.container {
    max-width: 400px;
    margin: 50px auto;
    padding: 30px;
    background-color: #fff; /* White */
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    background: linear-gradient(to bottom, #33ccff, #66ffff); /* Gradient */
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #2f4f7f; /* Deep blue */
}

.form-group {
    margin-bottom: 20px;
}

.form-control {
    height: 50px;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc; /* Light gray */
    border-radius: 5px;
    background-color: #f9f9f9; /* Lighter gray */
}

.form-control:focus {
    border-color: #66d9ef; /* Soft blue */
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

.btn-primary {
    width: 100%;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    background-color: #1a75ff; /* Vibrant blue */
    border-color: #1a75ff;
    color: #fff;
}

.btn-primary:hover {
    background-color: #1877f2; /* Deeper blue */
    border-color: #1877f2;
}

::placeholder {
    color: #999; /* Light gray */
    font-size: 14px;
}

@media (max-width: 768px) {
    .container {
        margin: 20px auto;
    }
}
    </style>
</head>
<body>
<div class="container">
    <h2>Your Cart</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['cart'] as $id => $quantity): ?>
                <?php if (isset($products[$id])): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($products[$id]['name']); ?></td>
                        <td>$<?php echo htmlspecialchars($products[$id]['price']); ?></td>
                        <td><?php echo htmlspecialchars($quantity); ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h3>Total: $<?php echo htmlspecialchars($total); ?></h3>
    <a href="checkout.php" class="btn btn-success">Checkout</a>
</div>
</body>
</html>


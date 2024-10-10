<?php
session_start();
include 'db.php';

// Check if user is logged in and cart is not empty
if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: products.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$total = 0;

// Debugging: Check session data
error_log("Session Data: " . print_r($_SESSION, true));

// Sanitize and filter the cart to remove invalid IDs
$_SESSION['cart'] = array_filter($_SESSION['cart'], function($key) {
    return is_numeric($key) && $key > 0;
}, ARRAY_FILTER_USE_KEY);

// Calculate the total price
foreach ($_SESSION['cart'] as $id => $quantity) {
    if (empty($id) || !is_numeric($id)) {
        die("Invalid product ID: $id");
    }

    $query = "SELECT price FROM products WHERE id = $id";
    $result = mysqli_query($conn, $query);
    
    // Check for errors in the query
    if (!$result) {
        die("Query failed: " . mysqli_error($conn) . " Query: $query");
    }

    // Check if the product exists
    if ($product = mysqli_fetch_assoc($result)) {
        $total += $product['price'] * $quantity;
    } else {
        die("Product not found for ID: $id");
    }
}

// Debugging: Check total amount
error_log("Total Amount: $total");

// Check if total is greater than zero
if ($total > 0) {
    // Check if user ID exists
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");

    // Debugging: Check user ID
    error_log("Checking for User ID: $user_id");

    if (mysqli_num_rows($result) > 0) {
        // User ID exists, proceed with the insertion
        $query = "INSERT INTO orders (user_id) VALUES ($user_id)"; // Adjusted query

        if (mysqli_query($conn, $query)) {
            $order_id = mysqli_insert_id($conn); // Get the order ID

            // Insert order items
            foreach ($_SESSION['cart'] as $id => $quantity) {
                $query = "SELECT price FROM products WHERE id = $id";
                $result = mysqli_query($conn, $query);
                
                if (!$result) {
                    die("Query failed: " . mysqli_error($conn) . " Query: $query");
                }

                if ($product = mysqli_fetch_assoc($result)) {
                    $price = $product['price'];
                    $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $id, $quantity, $price)";
                    if (!mysqli_query($conn, $query)) {
                        die("Order item insertion failed: " . mysqli_error($conn));
                    }
                } else {
                    die("Product not found for ID: $id");
                }
            }

            // Clear the cart
            $_SESSION['cart'] = []; // Clear the entire cart

            // Show a success message with PayPal form
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                <title>Checkout Successful</title>
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
                <h2>Checkout Successful</h2>
                <p>Your order has been placed successfully!</p>
                <p>Total Amount: $<?php echo htmlspecialchars($total); ?></p>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                    <input type="hidden" name="cmd" value="_xclick">
                    <input type="hidden" name="business" value="your_paypal_email@example.com">
                    <input type="hidden" name="item_name" value="Your Product Name">
                    <input type="hidden" name="amount" value="<?php echo htmlspecialchars($total); ?>">
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="hidden" name="return" value="http://yourwebsite.com/success.php">
                    <input type="hidden" name="cancel_return" value="http://yourwebsite.com/cancel.php">
                    <input type="submit" value="Pay with PayPal" class="btn btn-primary">
                </form>
                <a href="products.php" class="btn btn-primary">Continue Shopping</a>
            </div>
            </body>
            </html>
            <?php
        } else {
            die("Error inserting order: " . mysqli_error($conn));
        }
    } else {
        die("User ID does not exist in the users table.");
    }
} else {
    // If the total is 0, redirect to products
    header("Location: products.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>

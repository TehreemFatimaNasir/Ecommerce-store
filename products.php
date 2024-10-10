<?php
include 'db.php';

$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Products</title>
    <style>
   body {
    font-family: Arial, sans-serif;
    background: linear-gradient(to bottom, #45A0E6, #24A0BD); /* Calming Ocean */
}

.container {
    max-width: 1200px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff; /* White */
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #3498DB; /* Sky blue */
}

/* Card Styles */

.card {
    margin-bottom: 20px;
    border: none;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    background-color: #F7F7F7; /* Soft gray */
}

.card-img-top {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.card-body {
    padding: 20px;
}

.card-title {
    font-weight: bold;
    font-size: 18px;
    margin-bottom: 10px;
    color: #E74C3C; /* Burnt orange */
}

.card-text {
    font-size: 16px;
    margin-bottom: 10px;
    color: #777; /* Dark gray */
}

.price {
    font-size: 18px;
    font-weight: bold;
    color: #2ECC71; /* Mint green */
}

/* Button Styles */

.btn-primary {
    background-color: #9B59B6; /* Purple */
    border-color: #9B59B6;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    color: #fff;
}

.btn-primary:hover {
    background-color: #8E44AD; /* Deep purple */
    border-color: #8E44AD;
}

/* Responsive Design */

@media (max-width: 768px) {
    .card {
        margin-bottom: 10px;
    }
}

@media (max-width: 480px) {
    .card-img-top {
        height: 150px;
    }
}
    </style>
</head>
<body>
<div class="container">
    <h2>Product Listings</h2>
    <div class="row">
        <?php while ($product = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-4">
                <div class="card">
                    <img src="images/<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['name']; ?></h5>
                        <p class="card-text"><?php echo $product['description']; ?></p>
                        <p class="card-text">$<?php echo $product['price']; ?></p>
                        <a href="cart.php?action=add&id=<?php echo $product['id']; ?>" class="btn btn-primary">Add to Cart</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>

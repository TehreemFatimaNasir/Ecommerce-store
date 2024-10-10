<?php
session_start();
include 'db.php';

// Admin authentication would go here

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "images/$image");

    $query = "INSERT INTO products (name, description, price, image) VALUES ('$name', '$description', '$price', '$image')";
    mysqli_query($conn, $query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Admin Panel</title>
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
    <h2>Add Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <input type="text" name="name" class="form-control" placeholder="Product Name" required>
        </div>
        <div class="form-group">
            <textarea name="description" class="form-control" placeholder="Description" required></textarea>
        </div>
        <div class="form-group">
            <input type="number" name="price" class="form-control" placeholder="Price" required>
        </div>
        <div class="form-group">
            <input type="file" name="image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
</div>
</body>
</html>

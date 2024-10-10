<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: products.php");
        exit();
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Login</title>
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
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
    <form method="POST">
        <div class="form-group">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
</body>
</html>

<?php
require_once('./includes/db_connection.php'); // Include your database connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_products.php">Manage Products</a>
</body>
</html>

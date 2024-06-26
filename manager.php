<?php
    require_once('./includes/db_connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Manager Panel</title>
</head>
<body>
    <h1>Manager Panel</h1>
    <a href="manage_products.php">Manage Products</a>
    <a href="manage_invoices.php">Manage Invoices</a>
    <a href="manage_payments.php">Manage Payments</a>
</body>
</html>
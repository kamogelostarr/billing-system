<?php
    require_once('./includes/db_connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// $user = $_SESSION['user_id'];
$user = $_SESSION['user_id'];
// SELECT * FROM `users` WHERE `id` = 1

    $user_query = mysqli_query($conn, "SELECT * FROM `users` WHERE `id` = $user") or die('Query failed');
    if (mysqli_num_rows($user_query) > 0) {
        while ($fetch_user = mysqli_fetch_assoc($user_query)) {

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($fetch_user['username']); ?>!</h1>
    <p>Your role is: <?php echo htmlspecialchars($fetch_user['role']); ?></p>
    
    <?php if ($fetch_user['role'] == 'Admin'): ?>
        <a href="admin.php">Admin Panel</a>
    <?php elseif ($fetch_user['role'] == 'Manager'): ?>
        <a href="manager.php">Manager Panel</a>
    <?php elseif ($fetch_user['role'] == 'Clerk'): ?>
        <a href="clerk.php">Clerk Panel</a>
    <?php elseif ($fetch_user['role'] == 'Customer'): ?>
        <a href="customer.php">Customer Panel</a>
    <?php endif; 
        }
    }?>
    
    <a href="logout.php">Logout</a>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['user']) || ($_SESSION['user']['Role'] != 'Manager' && $_SESSION['user']['Role'] != 'Clerk')) {
    header("Location: login.php");
    exit();
}
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action == "create") {
        $customerID = $_POST['customerID'];
        $orderDate = $_POST['orderDate'];
        $totalAmount = $_POST['totalAmount'];
        
        $stmt = $conn->prepare("INSERT INTO Orders (CustomerID, OrderDate, TotalAmount) VALUES (:customerID, :orderDate, :totalAmount)");
        $stmt->bindParam(':customerID', $customerID);
        $stmt->bindParam(':orderDate', $orderDate);
        $stmt->bindParam(':totalAmount', $totalAmount);
        $stmt->execute();
    } elseif ($action == "delete") {
        $orderID = $_POST['orderID'];
        
        $stmt = $conn->prepare("DELETE FROM Orders WHERE OrderID = :orderID");
        $stmt->bindParam(':orderID', $orderID);
        $stmt->execute();
    }
}

$orders = $conn->query("SELECT * FROM Orders")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Manage Orders</title>
</head>
<body>
    <h1>Manage Orders</h1>
    
    <h2>Create Order</h2>
    <form action="manage_orders.php" method="post">
        <input type="hidden" name="action" value="create">
        <label for="customerID">Customer ID:</label>
        <input type="number" id="customerID" name="customerID" required>
        <br>
        <label for="orderDate">Order Date:</label>
        <input type="date" id="orderDate" name="orderDate" required>
        <br>
        <label for="totalAmount">Total Amount:</label>
        <input type="number" id="totalAmount" name="totalAmount" step="0.01" required>
        <br>
        <input type="submit" value="Create Order">
    </form>

    <h2>Existing Orders</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer ID</th>
            <th>Order Date</th>
            <th>Total Amount</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td><?php echo htmlspecialchars($order['OrderID']); ?></td>
            <td><?php echo htmlspecialchars($order['CustomerID']); ?></td>
            <td><?php echo htmlspecialchars($order['OrderDate']); ?></td>
            <td><?php echo htmlspecialchars($order['TotalAmount']); ?></td>
            <td>
                <form action="manage_orders.php" method="post" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="orderID" value="<?php echo $order['OrderID']; ?>">
                    <input type="submit" value="Delete">
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

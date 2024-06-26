<?php
require_once('./includes/db_connection.php'); // Include your database connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$customer_id = $_SESSION['user_id']; // Correctly get the customer ID from the session

// Prepare and execute the query
$invoices = $conn->prepare("SELECT * FROM Invoices WHERE customer_id = ?");
$invoices->bind_param('i', $customer_id);
$invoices->execute();
$result = $invoices->get_result();
$invoicesList = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Customer Panel</title>
</head>
<body>
    <h1>Customer Panel</h1>
    <h2>Your Invoices</h2>
    <table>
        <tr>
            <th>Invoice ID</th>
            <th>Invoice Date</th>
            <th>Total Amount</th>
            <th>Status</th>
        </tr>
        <?php foreach ($invoicesList as $invoice): ?>
        <tr>
            <td><?php echo htmlspecialchars($invoice['InvoiceID']); ?></td>
            <td><?php echo htmlspecialchars($invoice['InvoiceDate']); ?></td>
            <td><?php echo htmlspecialchars($invoice['TotalAmount']); ?></td>
            <td><?php echo htmlspecialchars($invoice['Status']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

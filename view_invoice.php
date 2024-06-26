<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if invoice ID is provided
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $invoiceID = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM Invoices WHERE InvoiceID = :invoiceID");
    $stmt->bindParam(':invoiceID', $invoiceID);
    $stmt->execute();
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$invoice) {
        die("Invoice not found");
    }

    // Fetch invoice items related to this invoice
    $stmtItems = $conn->prepare("SELECT * FROM InvoiceItems WHERE InvoiceID = :invoiceID");
    $stmtItems->bindParam(':invoiceID', $invoiceID);
    $stmtItems->execute();
    $invoiceItems = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
} else {
    die("Invoice ID not provided");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Invoice Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Invoice Details</h1>
        <h2>Invoice Information</h2>
        <p><strong>Invoice ID:</strong> <?= $invoice['InvoiceID'] ?></p>
        <p><strong>Customer ID:</strong> <?= $invoice['CustomerID'] ?></p>
        <p><strong>Invoice Date:</strong> <?= $invoice['InvoiceDate'] ?></p>
        <p><strong>Total Amount:</strong> <?= $invoice['TotalAmount'] ?></p>
        <p><strong>Status:</strong> <?= $invoice['Status'] ?></p>

        <h2>Invoice Items</h2>
        <?php if (count($invoiceItems) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoiceItems as $item): ?>
                        <tr>
                            <td><?= $item['ProductID'] ?></td>
                            <td><?= $item['Quantity'] ?></td>
                            <td><?= $item['UnitPrice'] ?></td>
                            <td><?= $item['TotalPrice'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No items found for this invoice.</p>
        <?php endif; ?>

        <a href="manager.php">Back to Dashboard</a> | <a href="logout.php">Logout</a>
    </div>
</body>
</html>

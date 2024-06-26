<?php
    require_once('./includes/db_connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'Manager' && $_SESSION['role'] !== 'Clerk') {
    die("Access denied");
}

// Check if invoice ID is provided
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $invoiceID = $_GET['id'];

    // Fetch invoice details
    $stmtInvoice = $conn->prepare("SELECT * FROM Invoices WHERE InvoiceID = :invoiceID");
    $stmtInvoice->bindParam(':invoiceID', $invoiceID);
    $stmtInvoice->execute();
    $invoice = $stmtInvoice->fetch(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="style.css">
    <title>Manage Invoice Items</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Manage Invoice Items</h1>
        <h2>Invoice Information</h2>
        <p><strong>Invoice ID:</strong> <?= $invoice['InvoiceID'] ?></p>
        <p><strong>Customer ID:</strong> <?= $invoice['CustomerID'] ?></p>
        <p><strong>Invoice Date:</strong> <?= $invoice['InvoiceDate'] ?></p>
        <p><strong>Total Amount:</strong> <?= $invoice['TotalAmount'] ?></p>
        <p><strong>Status:</strong> <?= $invoice['Status'] ?></p>

        <h2>Invoice Items</h2>
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoiceItems as $item): ?>
                    <tr>
                        <td><?= $item['ProductID'] ?></td>
                        <td><?= $item['Quantity'] ?></td>
                        <td><?= $item['UnitPrice'] ?></td>
                        <td><?= $item['TotalPrice'] ?></td>
                        <td>
                            <a href="edit_invoice_item.php?id=<?= $item['InvoiceItemID'] ?>">Edit</a> |
                            <a href="delete_invoice_item.php?id=<?= $item['InvoiceItemID'] ?>" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="add_invoice_item.php?id=<?= $invoiceID ?>">Add New Item</a> | <a href="manager.php">Back

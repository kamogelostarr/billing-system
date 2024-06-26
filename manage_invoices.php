<?php
require_once('./includes/db_connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action == "create") {
        $customer_id = $_POST['customer_id'];
        $invoice_date = $_POST['invoice_date'];
        $total_amount = $_POST['total_amount'];
        $status = $_POST['status'];
        
        // Prepare and bind parameters
        $stmt = $conn->prepare("INSERT INTO Invoices (customer_id, invoice_date, total_amount, Status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('isds', $customer_id, $invoice_date, $total_amount, $status);
        
        if ($stmt->execute()) {
            echo "Invoice created successfully.";
        } else {
            echo "Error creating invoice: " . $stmt->error;
        }
    } elseif ($action == "delete") {
        $invoice_id = $_POST['invoice_id'];
        
        // Prepare and bind parameter
        $stmt = $conn->prepare("DELETE FROM Invoices WHERE invoice_id = :invoice_id");
        $stmt->bind_param(':invoice_id', $invoice_id);
        
        if ($stmt->execute()) {
            echo "Invoice deleted successfully.";
        } else {
            echo "Error deleting invoice: " . $stmt->error;
        }
    }
}

// Fetch all invoices
$stmt = $conn->query("SELECT * FROM Invoices");
$invoices = $stmt;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Manage Invoices</title>
</head>
<body>
    <h1>Manage Invoices</h1>
    
    <h2>Create Invoice</h2>
    <form action="manage_invoices.php" method="post">
        <input type="hidden" name="action" value="create">
        <label for="customer_id">Customer ID:</label>
        <input type="number" id="customer_id" name="customer_id" required>
        <br>
        <label for="invoice_date">Invoice Date:</label>
        <input type="date" id="invoice_date" name="invoice_date" required>
        <br>
        <label for="total_amount">Total Amount:</label>
        <input type="number" id="total_amount" name="total_amount" step="0.01" required>
        <br>
        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="Pending">Pending</option>
            <option value="Paid">Paid</option>
            <option value="Cancelled">Cancelled</option>
        </select>
        <br>
        <input type="submit" value="Create Invoice">
    </form>

    <h2>Existing Invoices</h2>
    <table>
        <tr>
            <th>Invoice_ID</th>
            <th>Customer_ID</th>
            <th>Invoice_Date</th>
            <th>Total_Amount</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($invoices as $invoice): ?>
        <tr>
            <td><?php echo htmlspecialchars($invoice['invoice_id']); ?></td>
            <td><?php echo htmlspecialchars($invoice['customer_id']); ?></td>
            <td><?php echo htmlspecialchars($invoice['invoice_date']); ?></td>
            <td><?php echo htmlspecialchars($invoice['total_amount']); ?></td>
            <td><?php echo htmlspecialchars($invoice['Status']); ?></td>
            <td>
                <form action="manage_invoices.php" method="post" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="invoice_id" value="<?php echo $invoice['invoice_id']; ?>">
                    <input type="submit" value="Delete">
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

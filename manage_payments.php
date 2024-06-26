<?php
require_once('./includes/db_connection.php');

// Redirect to login if user session is not set
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form actions (create/delete payment)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action == "create") {
        $invoiceID = $_POST['invoiceID'];
        $paymentDate = $_POST['paymentDate'];
        $amount = $_POST['amount'];
        $paymentMethod = $_POST['paymentMethod'];
        
        $stmt = $conn->prepare("INSERT INTO Payments (InvoiceID, PaymentDate, Amount, PaymentMethod) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('isds', $invoiceID, $paymentDate, $amount, $paymentMethod);
        
        if ($stmt->execute()) {
            // Redirect after successful insert (optional)
            header("Location: manage_payments.php");
            exit();
        } else {
            echo "Error creating payment: " . $stmt->error;
        }
    } elseif ($action == "delete") {
        $paymentID = $_POST['paymentID'];
        
        $stmt = $conn->prepare("DELETE FROM Payments WHERE PaymentID = ?");
        $stmt->bind_param('i', $paymentID);
        
        if ($stmt->execute()) {
            // Redirect after successful delete (optional)
            header("Location: manage_payments.php");
            exit();
        } else {
            echo "Error deleting payment: " . $stmt->error;
        }
    }
}

// Fetch existing payments
$payments = $conn->query("SELECT * FROM Payments")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Manage Payments</title>
</head>
<body>
    <h1>Manage Payments</h1>
    
    <h2>Create Payment</h2>
    <form action="manage_payments.php" method="post">
        <input type="hidden" name="action" value="create">
        <label for="invoiceID">Invoice ID:</label>
        <input type="number" id="invoiceID" name="invoiceID" required>
        <br>
        <label for="paymentDate">Payment Date:</label>
        <input type="date" id="paymentDate" name="paymentDate" required>
        <br>
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" step="0.01" required>
        <br>
        <label for="paymentMethod">Payment Method:</label>
        <select id="paymentMethod" name="paymentMethod">
            <option value="Credit Card">Credit Card</option>
            <option value="Bank Transfer">Bank Transfer</option>
            <option value="Cash">Cash</option>
            <option value="Other">Other</option>
        </select>
        <br>
        <input type="submit" value="Create Payment">
    </form>

    <h2>Existing Payments</h2>
    <table>
        <tr>
            <th>Payment ID</th>
            <th>Invoice ID</th>
            <th>Payment Date</th>
            <th>Amount</th>
            <th>Payment Method</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($payments as $payment): ?>
        <tr>
            <td><?php echo htmlspecialchars($payment['PaymentID']); ?></td>
            <td><?php echo htmlspecialchars($payment['InvoiceID']); ?></td>
            <td><?php echo htmlspecialchars($payment['PaymentDate']); ?></td>
            <td><?php echo htmlspecialchars($payment['Amount']); ?></td>
            <td><?php echo htmlspecialchars($payment['PaymentMethod']); ?></td>
            <td>
                <form action="manage_payments.php" method="post" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="paymentID" value="<?php echo $payment['PaymentID']; ?>">
                    <input type="submit" value="Delete">
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

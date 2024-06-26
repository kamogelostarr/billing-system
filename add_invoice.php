<?php
    require_once('./includes/db_connection.php');
    
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    if ($_SESSION['role'] !== 'Manager' && $_SESSION['role'] !== 'Clerk') {
        die("Access denied");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $customerID = $_POST['customerID'];
        $invoiceDate = $_POST['invoiceDate'];
        $totalAmount = $_POST['totalAmount'];
        $status = $_POST['status'];

        // Insert invoice into database
        $stmt = $conn->prepare("INSERT INTO Invoices (CustomerID, InvoiceDate, TotalAmount, Status) VALUES (:customerID, :invoiceDate, :totalAmount, :status)");
        $stmt->bindParam(':customerID', $customerID);
        $stmt->bindParam(':invoiceDate', $invoiceDate);
        $stmt->bindParam(':totalAmount', $totalAmount);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            $invoiceID = $conn->lastInsertId();

            // Redirect to manage invoice items page with newly created invoice ID
            header("Location: manage_invoice_items.php?id=$invoiceID");
                exit();
            } else {
            echo "Error adding invoice";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Invoice</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Add New Invoice</h1>
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <label for="customerID">Customer ID:</label>
            <input type="text" id="customerID" name="customerID" required><br><br>
            <label for="invoiceDate">Invoice Date:</label>
            <input type="date" id="invoiceDate" name="invoiceDate" required><br><br>
            <label for="totalAmount">Total Amount:</label>
            <input type="text" id="totalAmount" name="totalAmount" required><br><br>
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="Pending">Pending</option>
                <option value="Paid">Paid</option>
                <option value="Cancelled">Cancelled</option>
            </select><br><br>
            <input type="submit" value="Add Invoice">
        </form>
        <a href="manager.php">Back to Dashboard</a> | <a href="logout.php">Logout</a>
    </div>
</body>
</html>

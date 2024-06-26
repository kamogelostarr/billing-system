<?php
    require_once('./includes/db_connection.php');
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'Manager' && $_SESSION['role'] !== 'Clerk') {
    die("Access denied");
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $invoiceID = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM Invoices WHERE InvoiceID = :invoiceID");
    $stmt->bindParam(':invoiceID', $invoiceID);
    $stmt->execute();
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$invoice) {
        die("Invoice not found");
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $invoiceID = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE Invoices SET Status = :status WHERE InvoiceID = :invoiceID");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':invoiceID', $invoiceID);

    if ($stmt->execute()) {
        header("Location: manager.php"); // Redirect to manager's dashboard
        exit();
    } else {
        echo "Error updating invoice";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Edit Invoice</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Invoice</h1>
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <input type="hidden" name="id" value="<?= $invoice['InvoiceID'] ?>">
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="Pending" <?= ($invoice['Status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                <option value="Paid" <?= ($invoice['Status'] == 'Paid') ? 'selected' : '' ?>>Paid</option>
                <option value="Cancelled" <?= ($invoice['Status'] == 'Cancelled') ? 'selected' : '' ?>>Cancelled</option>
            </select><br><br>
            <input type="submit" value="Update Invoice">
        </form>
        <a href="manager.php">Back to Dashboard</a> | <a href="logout.php">Logout</a>
    </div>
</body>
</html>

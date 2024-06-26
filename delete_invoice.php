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

    $stmt = $conn->prepare("DELETE FROM Invoices WHERE InvoiceID = :invoiceID");
    $stmt->bindParam(':invoiceID', $invoiceID);

    if ($stmt->execute()) {
        // Also delete associated invoice items, if needed
        $stmtItems = $conn->prepare("DELETE FROM InvoiceItems WHERE InvoiceID = :invoiceID");
        $stmtItems->bindParam(':invoiceID', $invoiceID);
        $stmtItems->execute();

        header("Location: manager.php"); // Redirect to manager's dashboard
        exit();
    } else {
        echo "Error deleting invoice";
    }
}
?>

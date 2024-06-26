<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Define pagination variables
$records_per_page = 10;
$page = 1;

// Check if page number is specified
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = intval($_GET['page']);
}

// Calculate OFFSET for pagination
$offset = ($page - 1) * $records_per_page;

// Fetch invoices with search and filter criteria
$query = "SELECT * FROM Invoices WHERE 1=1";

if (!empty($_GET['customerID'])) {
    $query .= " AND CustomerID = :customerID";
}

if (!empty($_GET['status'])) {
    $query .= " AND Status = :status";
}

// Count total records (for pagination)
$stmtCount = $conn->prepare($query);
if (!empty($_GET['customerID'])) {
    $stmtCount->bindParam(':customerID', $_GET['customerID']);
}
if (!empty($_GET['status'])) {
    $stmtCount->bindParam(':status', $_GET['status']);
}
$stmtCount->execute();
$total_records = $stmtCount->rowCount();

// Fetch invoices for the current page
$query .= " LIMIT :offset, :records_per_page";
$stmt = $conn->prepare($query);
if (!empty($_GET['customerID'])) {
    $stmt->bindParam(':customerID', $_GET['customerID']);
}
if (!empty($_GET['status'])) {
    $stmt->bindParam(':status', $_GET['status']);
}
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch distinct customer IDs for dropdown filter
$stmtCustomers = $conn->query("SELECT DISTINCT CustomerID FROM Invoices");
$customers = $stmtCustomers->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Search and Filter Invoices</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Search and Filter Invoices</h1>
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="get">
            <label for="customerID">Customer ID:</label>
            <select id="customerID" name="customerID">
                <option value="">-- Select Customer --</option>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= $customer ?>" <?= ($_GET['customerID'] == $customer) ? 'selected' : '' ?>><?= $customer ?></option>
                <?php endforeach; ?>
            </select><br><br>
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="">-- Select Status --</option>
                <option value="Pending" <?= ($_GET['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                <option value="Paid" <?= ($_GET['status'] == 'Paid') ? 'selected' : '' ?>>Paid</option>
                <option value="Cancelled" <?= ($_GET['status'] == 'Cancelled') ? 'selected' : '' ?>>Cancelled</option>
            </select><br><br>
            <input type="submit" value="Apply Filter">
        </form>
        
        <h2>Filtered Invoices</h2>
        <table>
            <thead>
                <tr>
                    <th>InvoiceID</th>
                    <th>CustomerID</th>
                    <th>InvoiceDate</th>
                    <th>TotalAmount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td><?= $invoice['InvoiceID'] ?></td>
                        <td><?= $invoice['CustomerID'] ?></td>
                        <td><?= $invoice['InvoiceDate'] ?></td>
                        <td><?= $invoice['TotalAmount'] ?></td>
                        <td><?= $invoice['Status'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($total_records > $records_per_page): ?>
            <div class="pagination">
                <?php
                $total_pages = ceil($total_records / $records_per_page);
                for ($i = 1; $i <= $total_pages; $i++):
                    ?>
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $i ?>&<?= http_build_query($_GET) ?>" <?= ($page == $i) ? 'class="active"' : '' ?>><?= $i ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <a href="manager.php">Back to Dashboard</a> | <a href="logout.php">Logout</a>
    </div>
</body>
</html>

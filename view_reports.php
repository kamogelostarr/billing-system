<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] != 'Admin') {
    header("Location: login.php");
    exit();
}
include 'db.php';

$reportType = isset($_GET['reportType']) ? $_GET['reportType'] : 'sales';

if ($reportType == 'sales') {
    $report = $conn->query("SELECT * FROM SalesReport")->fetchAll(PDO::FETCH_ASSOC);
} elseif ($reportType == 'inventory') {
    $report = $conn->query("SELECT * FROM InventoryReport")->fetchAll(PDO::FETCH_ASSOC);
} elseif ($reportType == 'customer') {
    $report = $conn->query("SELECT * FROM CustomerReport")->fetchAll(PDO::FETCH_ASSOC);
} else {
    $report = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Reports</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>View Reports</h1>
    
    <form action="view_reports.php" method="get">
        <label for="reportType">Select Report Type:</label>
        <select id="reportType" name="reportType">
            <option value="sales" <?php if ($reportType == 'sales') echo 'selected'; ?>>Sales Report</option>
            <option value="inventory" <?php if ($reportType == 'inventory') echo 'selected'; ?>>Inventory Report</option>
            <option value="customer" <?php if ($reportType == 'customer') echo 'selected'; ?>>Customer Report</option>
        </select>
        <input type="submit" value="View Report">
    </form>

    <h2><?php echo ucfirst($reportType); ?> Report</h2>
    <table>
        <tr>
            <?php if (!empty($report)): ?>
                <?php foreach (array_keys($report[0]) as $column): ?>
                    <th><?php echo htmlspecialchars($column); ?></th>
                <?php endforeach; ?>
            <?php endif; ?>
        </tr>
        <?php foreach ($report as $row): ?>
        <tr>
            <?php foreach ($row as $column => $value): ?>
                <td><?php echo htmlspecialchars($value); ?></td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

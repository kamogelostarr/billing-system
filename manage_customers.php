<?php
session_start();
if (!isset($_SESSION['user']) || ($_SESSION['user']['Role'] != 'Admin' && $_SESSION['user']['Role'] != 'Manager')) {
    header("Location: login.php");
    exit();
}
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action == "create") {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        
        $stmt = $conn->prepare("INSERT INTO Customers (FirstName, LastName, Email, Phone, Address) VALUES (:firstName, :lastName, :email, :phone, :address)");
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->execute();
    } elseif ($action == "delete") {
        $customerID = $_POST['customerID'];
        
        $stmt = $conn->prepare("DELETE FROM Customers WHERE CustomerID = :customerID");
        $stmt->bindParam(':customerID', $customerID);
        $stmt->execute();
    }
}

$customers = $conn->query("SELECT * FROM Customers")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Manage Customers</title>
</head>
<body>
    <h1>Manage Customers</h1>
    
    <h2>Create Customer</h2>
    <form action="manage_customers.php" method="post">
        <input type="hidden" name="action" value="create">
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" required>
        <br>
        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone">
        <br>
        <label for="address">Address:</label>
        <textarea id="address" name="address"></textarea>
        <br>
        <input type="submit" value="Create Customer">
    </form>

    <h2>Existing Customers</h2>
    <table>
        <tr>
            <th>Customer ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($customers as $customer): ?>
        <tr>
            <td><?php echo htmlspecialchars($customer['CustomerID']); ?></td>
            <td><?php echo htmlspecialchars($customer['FirstName']); ?></td>
            <td><?php echo htmlspecialchars($customer['LastName']); ?></td>
            <td><?php echo htmlspecialchars($customer['Email']); ?></td>
            <td><?php echo htmlspecialchars($customer['Phone']); ?></td>
            <td><?php echo htmlspecialchars($customer['Address']); ?></td>
            <td>
                <form action="manage_customers.php" method="post" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="customerID" value="<?php echo $customer['CustomerID']; ?>">
                    <input type="submit" value="Delete">
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

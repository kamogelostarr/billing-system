<?php
require_once('./includes/db_connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action == "create") {
        $product_name = $_POST['product_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        
        // Prepare and bind parameters
        $stmt = $conn->prepare("INSERT INTO products (product_name, description, price) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $product_name, $description, $price); // 'd' for double (Price)
        
        if ($stmt->execute()) {
            echo "Product created successfully.";
        } else {
            echo "Error creating product: " . $stmt->error;
        }
    } elseif ($action == "delete") {
        $product_id = $_POST['product_id'];
        
        // Prepare and bind parameter
        $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id); // 'i' for integer (product_id)
        
        if ($stmt->execute()) {
            echo "Product deleted successfully.";
        } else {
            echo "Error deleting product: " . $stmt->error;
        }
    }
}

// Fetch all products
$products = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Manage Products</title>
</head>
<body>
    <h1>Manage Products</h1>
    
    <h2>Create Product</h2>
    <form action="manage_products.php" method="post">
        <input type="hidden" name="action" value="create">
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required>
        <br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <br>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>
        <br>
        <input type="submit" value="Create Product">
    </form>

    <h2>Existing Products</h2>
    <table>
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo htmlspecialchars($product['product_id']); ?></td>
            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
            <td><?php echo htmlspecialchars($product['description']); ?></td>
            <td><?php echo htmlspecialchars($product['price']); ?></td>
            <td>
                <form action="manage_products.php" method="post" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                    <input type="submit" value="Delete">
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

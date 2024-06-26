<?php
require_once('./includes/db_connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action == "create") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $role = $_POST['role'];
    
        if ($password === $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
            // Prepare and bind
            $query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
                if ($stmt->execute()) {
                    // echo "Registration successful. You can now <a href='login.php'>login</a>.";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        } else {
            echo "Passwords do not match.";
        }
    } elseif ($action == "delete") {
        $userID = $_POST['userID'];
        
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param('i', $userID);
        if ($stmt->execute()) {
            echo "User deleted successfully.";
        } else {
            echo "Error deleting user: " . $stmt->error;
        }
        $stmt->close();
    }
}

$users = $conn->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Manage Users</title>
</head>
<body>
    <h1>Manage Users</h1>
    
    <h2>Create User</h2>
    <form action="manage_users.php" method="post">
        <input type="hidden" name="action" value="create">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <br>
        <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="Admin">Admin</option>
            <option value="Manager">Manager</option>
            <option value="Clerk">Clerk</option>
            <option value="Customer">Customer</option>
        </select>
        <br>
        <input type="submit" value="Create User">
    </form>

    <h2>Existing Users</h2>
    <table>
        <tr>
            <th>UserID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while ($user = $users->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['role']); ?></td>
            <td>
                <form action="manage_users.php" method="post" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="userID" value="<?php echo htmlspecialchars($user['id']); ?>">
                    <input type="submit" value="Delete">
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

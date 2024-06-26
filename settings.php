<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] != 'Admin') {
    header("Location: login.php");
    exit();
}
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $settingName = $_POST['settingName'];
    $settingValue = $_POST['settingValue'];
    
    $stmt = $conn->prepare("UPDATE Settings SET SettingValue = :settingValue WHERE SettingName = :settingName");
    $stmt->bindParam(':settingName', $settingName);
    $stmt->bindParam(':settingValue', $settingValue);
    $stmt->execute();
}

$settings = $conn->query("SELECT * FROM Settings")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Settings</title>
</head>
<body>
    <h1>Settings</h1>
    
    <h2>Update Setting</h2>
    <form action="settings.php" method="post">
        <label for="settingName">Setting Name:</label>
        <select id="settingName" name="settingName">
            <?php foreach ($settings as $setting): ?>
                <option value="<?php echo htmlspecialchars($setting['SettingName']); ?>"><?php echo htmlspecialchars($setting['SettingName']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="settingValue">Setting Value:</label>
        <input type="text" id="settingValue" name="settingValue" required>
        <br>
        <input type="submit" value="Update Setting">
    </form>

    <h2>Current Settings</h2>
    <table>
        <tr>
            <th>Setting Name</th>
            <th>Setting Value</th>
        </tr>
        <?php foreach ($settings as $setting): ?>
        <tr>
            <td><?php echo htmlspecialchars($setting['SettingName']); ?></td>
            <td><?php echo htmlspecialchars($setting['SettingValue']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

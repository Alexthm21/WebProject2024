<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../db_connect.php';

$message = "";

// Fetch categories for the dropdown
$sql = "SELECT id, name FROM categories";
$result = $conn->query($sql);
$categories = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $category_id = $_POST['category_id'];

    // Correct SQL statement to match your table structure
    $sql = "INSERT INTO items (name, category_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $item_name, $category_id);

    try {
        if ($stmt->execute()) {
            $message = "Item added successfully!";
        }
    } catch (mysqli_sql_exception $e) {
        $message = "Error: " . $e->getMessage();
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
<div class="container">
    <h2>Add Item</h2>
    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    <form action="add_item.php" method="post">
        <label for="item_name">Item Name:</label>
        <input type="text" id="item_name" name="item_name" required><br>
        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
            <?php endforeach; ?>
        </select><br>
        <button type="submit">Add Item</button>
    </form>
    <a href="admin_dashboard.php">Back to Admin Dashboard</a>
</div>
</body>
</html>

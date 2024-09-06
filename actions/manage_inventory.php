<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../db_connect.php';

// Ανάκτηση όλων των προϊόντων και των κατηγοριών τους μαζί με την ποσότητα
$items_result = $conn->query("SELECT items.id, items.name, items.quantity, categories.category_name FROM items JOIN categories ON items.category_id = categories.id");
$items = [];
while ($row = $items_result->fetch_assoc()) {
    $items[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inventory</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
<div class="container">
    <h2>Manage Inventory</h2>
    <a href="add_item.php">Add New Item</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Quantity</th> <!-- Προσθήκη της νέας στήλης -->
            <th>Actions</th>
        </tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo $item['id']; ?></td>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['category_name']; ?></td>
                <td><?php echo $item['quantity']; ?></td> <!-- Εμφάνιση της ποσότητας -->
                <td>
                    <a href="edit_inventory.php?id=<?php echo $item['id']; ?>">Edit</a>
                    <a href="delete_inventory.php?id=<?php echo $item['id']; ?>">Delete</a>
                    <a href="update_quantity.php?id=<?php echo $item['id']; ?>">Update Quantity</a> <!-- Νέος σύνδεσμος για ενημέρωση ποσότητας -->
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="../dashboards/admin_dashboard.php" class="back-button">Back to Admin Dashboard</a>
</div>
</body>
</html>
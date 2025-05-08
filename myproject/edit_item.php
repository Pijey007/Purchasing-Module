<?php
include 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$item = null;

// Fetch item data
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM item_file WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    $stmt->close();
}

// Update item on form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST['code'];
    $name = $_POST['name'];
    $unit_cost = floatval($_POST['unit_cost']);
    $required_inventory = intval($_POST['required_inventory']);
    $reorder_point = intval($_POST['reorder_point']);

    $product_type_array = isset($_POST['product_type']) ? $_POST['product_type'] : [];
    $product_type = implode(", ", $product_type_array);
    $track_inventory = isset($_POST['track_inventory']) ? 1 : 0;

    $update = $conn->prepare("UPDATE item_file SET code=?, name=?, unit_cost=?, required_inventory=?, reorder_point=?, product_type=?, track_inventory=? WHERE id=?");
    $update->bind_param("ssdiisii", $code, $name, $unit_cost, $required_inventory, $reorder_point, $product_type, $track_inventory, $id);
    $update->execute();
    $update->close();

    header("Location: item_file.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="myproject/css/item.css">
    <title>Edit Item</title>
</head>
<body>
<div class="container">
    <h2>Edit Item</h2>
    <?php if ($item): ?>
    <form method="POST">
        <div class="form-grid">
            <div class="form-group">
                <label>Code</label>
                <input type="text" name="code" value="<?= htmlspecialchars($item['code']) ?>" required>
            </div>
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Unit Cost</label>
                <input type="number" step="0.01" name="unit_cost" value="<?= $item['unit_cost'] ?>" required>
            </div>
            <div class="form-group">
                <label>Required Inventory</label>
                <input type="number" name="required_inventory" value="<?= $item['required_inventory'] ?>" required>
            </div>
            <div class="form-group">
                <label>Reorder Point</label>
                <input type="number" name="reorder_point" value="<?= $item['reorder_point'] ?>" required>
            </div>
            <div class="form-group">
                <label>Product Type</label>
                <div class="checkbox-group">
                    <?php $types = explode(", ", $item['product_type']); ?>
                    <label><input type="checkbox" name="product_type[]" value="Good" <?= in_array("Good", $types) ? 'checked' : '' ?>> Good</label>
                    <label><input type="checkbox" name="product_type[]" value="Service" <?= in_array("Service", $types) ? 'checked' : '' ?>> Service</label>
                    <label><input type="checkbox" name="product_type[]" value="Combo" <?= in_array("Combo", $types) ? 'checked' : '' ?>> Combo</label>
                </div>
            </div>
            <div class="form-group" style="grid-column: span 2;">
                <label><input type="checkbox" name="track_inventory" <?= $item['track_inventory'] ? 'checked' : '' ?>> Track Inventory</label>
            </div>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <button type="submit">Update</button>
            <a href="item_file.php" class="btn-delete" style="margin-left: 10px;">Cancel</a>
        </div>
    </form>
    <?php else: ?>
        <p style="color: red;">Item not found.</p>
    <?php endif; ?>
</div>
</body>
</html>

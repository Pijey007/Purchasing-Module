<?php
include 'db.php';

$id = intval($_GET['id']);
$request = $conn->query("SELECT * FROM purchase_requests WHERE id = $id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemName = $_POST['itemName'];
    $quantity = $_POST['quantity'];
    $urgency = $_POST['urgency'];
    $department = $_POST['department'];

    $stmt = $conn->prepare("UPDATE purchase_requests SET item_name=?, quantity=?, urgency=?, department=? WHERE id=?");
    $stmt->bind_param("sissi", $itemName, $quantity, $urgency, $department, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Request updated successfully!'); window.location.href='purchase_request.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="myproject/css/item.css">
    <title>Edit Purchase Request</title>
</head>
<body>
<div class="content">
    <h2>Edit Purchase Request</h2>
    <form method="POST">
        <div class="form-group">
            <label>Item Name</label>
            <input type="text" name="itemName" value="<?= htmlspecialchars($request['item_name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" value="<?= $request['quantity'] ?>" required>
        </div>
        <div class="form-group">
            <label>Urgency</label>
            <select name="urgency" required>
                <option value="Low" <?= $request['urgency'] == 'Low' ? 'selected' : '' ?>>Low</option>
                <option value="Medium" <?= $request['urgency'] == 'Medium' ? 'selected' : '' ?>>Medium</option>
                <option value="High" <?= $request['urgency'] == 'High' ? 'selected' : '' ?>>High</option>
            </select>
        </div>
        <div class="form-group">
            <label>Department</label>
            <input type="text" name="department" value="<?= htmlspecialchars($request['department']) ?>" required>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <button type="submit">Update</button>
            <a href="purchase_request.php" class="btn-delete" style="margin-left: 10px;">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>

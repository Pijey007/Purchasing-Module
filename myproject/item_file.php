<?php
include 'db.php';
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST['code'];
    $name = $_POST['name'];
    $unit_cost = floatval($_POST['unit_cost']);
    $required_inventory = intval($_POST['required_inventory']);
    $reorder_point = intval($_POST['reorder_point']);
    $product_type_array = isset($_POST['product_type']) ? $_POST['product_type'] : [];
    $product_type = implode(", ", $product_type_array);
    $track_inventory = isset($_POST['track_inventory']) ? 1 : 0;

    $sql = "INSERT INTO item_file (code, name, unit_cost, required_inventory, reorder_point, product_type, track_inventory)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssdiisi", $code, $name, $unit_cost, $required_inventory, $reorder_point, $product_type, $track_inventory);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Prepare failed: " . $conn->error;
    }
}

// Fetch items for table
$items = [];
$result = $conn->query("SELECT * FROM item_file ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="myproject/css/item.css">
  <title>Purchasing System - Item File</title>
</head>
<body>
<div class="container">
  <h2>Item File</h2>
  <form method="POST" action="">
    <div class="form-grid">
      <div class="form-group">
        <label>Code</label>
        <input type="text" name="code" required>
      </div>
      <div class="form-group">
        <label>Product Name</label>
        <input type="text" name="name" required>
      </div>
      <div class="form-group">
        <label>Unit Cost</label>
        <input type="number" step="0.01" name="unit_cost" required>
      </div>
      <div class="form-group">
        <label>Required Inventory</label>
        <input type="number" name="required_inventory" required>
      </div>
      <div class="form-group">
        <label>Reorder Point</label>
        <input type="number" name="reorder_point" required>
      </div>
      <div class="form-group">
        <label>Product Type</label>
        <div class="checkbox-group">
          <label><input type="checkbox" name="product_type[]" value="Good"> Good</label>
          <label><input type="checkbox" name="product_type[]" value="Service"> Service</label>
          <label><input type="checkbox" name="product_type[]" value="Combo"> Combo</label>
        </div>
      </div>
      <div class="form-group" style="grid-column: span 2;">
        <label><input type="checkbox" name="track_inventory"> Track Inventory</label>
      </div>
    </div>
    <div style="text-align: center; margin-top: 350px;">
      <button type="submit">Submit</button>
    </div>
  </form>
</div>

  <!-- Table View -->
  <h2>Submitted Items</h2>
  <table>
    <thead>
    <tr>
      <th>Code</th>
      <th>Product Name</th>
      <th>Unit Cost</th>
      <th>Required Inventory</th>
      <th>Reorder Point</th>
      <th>Product Type</th>
      <th>Track Inventory</th>
      <th>Actions</th>
    </tr>
  </thead>

    <tbody>
      
      <?php foreach ($items as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item['code']) ?></td>
          <td><?= htmlspecialchars($item['name']) ?></td>
          <td><?= number_format($item['unit_cost'], 2) ?></td>
          <td><?= $item['required_inventory'] ?></td>
          <td><?= $item['reorder_point'] ?></td>
          <td><?= htmlspecialchars($item['product_type']) ?></td>
          <td><?= $item['track_inventory'] ? 'Yes' : 'No' ?></td>
          <td>
  <a href="edit_item.php?id=<?= $item['id'] ?>" class="btn-edit">Edit</a>
  <a href="delete_item.php?id=<?= $item['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
</td>

        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<div class="return">
  <a href="dashboard.php">← Back to Dashboard</a>
</div>

<script src="myproject/JS/item.js"></script>

</body>
</html>

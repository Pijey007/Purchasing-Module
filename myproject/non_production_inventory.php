<?php
include 'db.php';


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descriptions = $_POST['description'];
    $item_codes = $_POST['item_code'];
    $units = $_POST['units'];
    $balances = $_POST['balance_inventory'];
    $triggers = $_POST['trigger_quantity'];
    $costs = $_POST['cost_per_unit'];

    $sql = "INSERT INTO non_production_inventory (item_description, item_code, units, balance_required, trigger_quantity, cost_per_unit)
        VALUES (?, ?, ?, ?, ?, ?)";


    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    for ($i = 0; $i < count($descriptions); $i++) {
        $stmt->bind_param(
            "sssidd",
            $descriptions[$i],
            $item_codes[$i],
            $units[$i],
            $balances[$i],
            $triggers[$i],
            $costs[$i]
        );
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    echo "<script>alert('Inventory saved successfully!'); window.location.href='non_production_inventory.php';</script>";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="myproject/css/inventory_form.css">
    <title>Non-Production Supplies Inventory</title>
    
</head>
<body>
    <h1>Non-Production Supplies Inventory</h1>

    <form action="" method="post">
        <table id="inventoryTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Item Description</th>
                    <th>Item Code</th>
                    <th>Units</th>
                    <th>Balance Required Inventory</th>
                    <th>Trigger Quantity</th>
                    <th>Cost per Unit</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr>
                    <td>1</td>
                    <td><input type="text" name="description[]"></td>
                    <td><input type="text" name="item_code[]"></td>
                    <td><input type="text" name="units[]"></td>
                    <td><input type="number" name="balance_inventory[]" min="0"></td>
                    <td><input type="number" name="trigger_quantity[]" min="0"></td>
                    <td><input type="number" name="cost_per_unit[]" step="0.01" min="0"></td>
                    <td><button type="button" class="delete-btn" onclick="deleteRow(this)">Delete</button></td>
                </tr>
            </tbody>
        </table>
    
        <button type="button" onclick="addRow()">Add Row</button>
        <button type="submit">Submit</button>
        <button type="button"><a href="dashboard.php ">Return</a></button>
    </form>

    <script src="myproject/JS/inventory_form.js"></script>
</body>
</html>

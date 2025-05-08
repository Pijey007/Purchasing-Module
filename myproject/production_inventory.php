<?php
include 'db.php';
// FIX: Query to fetch inventory data
$sql = "SELECT 
            i.id, 
            i.item_code, 
            i.item_name, 
            i.unit, 
            COALESCE(SUM(s.quantity_received), 0) AS running_balance,
            MAX(s.unit_cost) AS cost_per_unit,
            i.reorder_point AS trigger_quantity
        FROM inventory_items i
        LEFT JOIN inventory_stock s ON i.id = s.item_id
        GROUP BY i.id, i.item_code, i.item_name, i.unit, i.reorder_point
        ORDER BY i.item_code ASC";

$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="myproject/css/inventory_form.css">
    <title>Production Supplies Inventory</title>
    
</head>
<body>
<h1>Production Supplies Inventory</h1>

<div class="search-bar">
    <label for="search">Search by Item Code:</label>
    <input type="text" id="search" onkeyup="filterTable()" placeholder="Enter item code...">
</div>

<table id="inventoryTable">
    <thead>
        <tr>
            <th>No.</th>
            <th>Item Code</th>
            <th>Item Description</th>
            <th>Units</th>
            <th>Running Balance</th>
            <th>Cost per Unit</th>
            <th>Balance Required Inventory</th>
            <th>Trigger Quantity</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                $balance_required = max(0, $row['trigger_quantity'] - $row['running_balance']);
                echo "<tr>
                    <td>{$no}</td>
                    <td>{$row['item_code']}</td>
                    <td>{$row['item_name']}</td>
                    <td>{$row['unit']}</td>
                    <td>{$row['running_balance']}</td>
                    <td>{$row['cost_per_unit']}</td>
                    <td>{$balance_required}</td>
                    <td>{$row['trigger_quantity']}</td>
                </tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='8'>No inventory items found.</td></tr>";
        }
        ?>
    </tbody>
</table>

        <button type="button"><a href="dashboard.php ">← Back to Dashboard</a></button>
    </form>

    <script src="myproject/JS/inventory_form.js"></script>
</body>
</html>

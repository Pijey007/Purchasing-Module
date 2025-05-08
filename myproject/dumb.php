<?php
$conn = new mysqli("localhost", "root", "", "purchasing_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $po_number = isset($_POST['po_number']) ? $_POST['po_number'] : '';
    $mrr_date = isset($_POST['mrr_date']) ? $_POST['mrr_date'] : '';
    $receiver_name = isset($_POST['receiver_name']) ? $_POST['receiver_name'] : '';

    // Insert into mrr table
    $stmt = $conn->prepare("INSERT INTO mrr (po_number, mrr_date, receiver_name) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $po_number, $mrr_date, $receiver_name);
    $stmt->execute();
    $mrr_id = $stmt->insert_id;
    $stmt->close();

    // Loop through items
    if (isset($_POST['item_id']) && is_array($_POST['item_id'])) {
        foreach ($_POST['item_id'] as $index => $item_id) {
            $delivered_qty = isset($_POST['delivered_quantity'][$index]) ? $_POST['delivered_quantity'][$index] : 0;
            $unit_cost = isset($_POST['unit_cost'][$index]) ? $_POST['unit_cost'][$index] : 0;

            // Insert into mrr_transactions
            $stmt = $conn->prepare("INSERT INTO mrr_transactions (mrr_id, item_id, quantity_received, unit_cost, received_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiids", $mrr_id, $item_id, $delivered_qty, $unit_cost, $mrr_date);
            $stmt->execute();
            $stmt->close();

            // Update inventory_stock table
            $stmt = $conn->prepare("INSERT INTO inventory_stock (item_id, mrr_id, quantity_received, unit_cost, received_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiids", $item_id, $mrr_id, $delivered_qty, $unit_cost, $mrr_date);
            $stmt->execute();
            $stmt->close();

            // Check if item is now fully delivered and update status
            $stmt = $conn->prepare("SELECT quantity_ordered, status FROM purchase_order_items WHERE po_number = ? AND item_id = ?");
            $stmt->bind_param("si", $po_number, $item_id);
            $stmt->execute();
            $stmt->bind_result($quantity_ordered, $status);
            if ($stmt->fetch()) {
                $stmt->close();

                // Sum of delivered so far
                $stmt = $conn->prepare("SELECT SUM(quantity_received) FROM mrr_transactions WHERE item_id = ? AND mrr_id IN (SELECT id FROM mrr WHERE po_number = ?)");
                $stmt->bind_param("is", $item_id, $po_number);
                $stmt->execute();
                $stmt->bind_result($total_received);
                $stmt->fetch();
                $stmt->close();

                if ($total_received >= $quantity_ordered) {
                    $stmt = $conn->prepare("UPDATE purchase_order_items SET status = 'Closed' WHERE po_number = ? AND item_id = ?");
                    $stmt->bind_param("si", $po_number, $item_id);
                    $stmt->execute();
                    $stmt->close();
                }
            } else {
                $stmt->close();
            }
        }
    }

    echo "<script>alert('Material Receiving Report saved successfully!'); window.location.href='material_receiving_report.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="myproject/css/material_receiving.css">
    <title>Material Receiving Report</title>
</head>
<body>
    <h2>Material Receiving Report (MRR)</h2>

    <form method="GET" enctype="multipart/form-data">

        <label>Select Purchase Order:</label>
        <select name="po_id" onchange="this.form.submit()" required>
            <option value="">-- Choose PO --</option>
            <?php
            $po_list = $conn->query("SELECT id, po_no FROM purchase_orders");
            while ($row = $po_list->fetch_assoc()) {
                $selected = ($row['id'] == $po_id) ? "selected" : "";
                echo "<option value='{$row['id']}' $selected>PO No: {$row['po_no']}</option>";
            }
            ?>
        </select>
    </form>

    <?php if ($po_info): ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="po_id" value="<?= $po_id ?>">
            <label>MRR No:</label>
            <input type="text" name="mrr_no" required>

            <label>Received Date:</label>
            <input type="date" name="received_date" required>

            <label>Received By:</label>
            <input type="text" name="received_by" required>

            <label>DR NO:</label>
            <input type="text" name="dr_no" required>

            <label>DR Personel</label>
            <input type="text" name="dr_personel" required>

            <label for="drDocs">DR Document</label>
            <input type="file" id="drDocs" name="drDocs">


            <h3>Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Description</th>
                        <th>Ordered Qty</th>
                        <th>Received Qty</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($po_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['item']) ?></td>
                            <td><?= htmlspecialchars($item['description']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>
                                <input type="number" name="received_qty[]" min="0" required>
                                <input type="hidden" name="item_id[]" value="<?= $item['id'] ?>">
                            </td>
                            <td><input type="text" name="remarks[]"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" name="submit_mrr">Submit MRR</button>
        </form>
    <?php endif; ?>
    <button type="button" class="return"><a href="dashboard.php ">← Back to Dashboard</a></button>
</body>
</html>













<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="myproject/css/create_asset.css">
    <title>Create Asset Card</title>
</head>
<body>
    <h2>Asset Card Entry Form</h2>
    <form class="form-grid">
        <!-- Column 1 -->
        <div class="form-column">
            <label>Asset Name</label>
            <input type="text" name="asset_name" required>

            <label>Category</label>
            <select name="category" required>
                <option value="">-- Select Category --</option>
                <option value="Computer">Computer</option>
                <option value="Desks">Desks</option>
                <option value="Chairs">Chairs</option>
                <option value="Computer Peripheral">Computer Peripheral</option>
                <option value="Printer">Printer</option>
                <option value="Furnitures and Fiturex">Furnitures and Fiturex</option>
            </select>

            <label>Model</label>
            <input type="text" name="model" required>

            <label>Brand</label>
            <input type="text" name="brand" required>

            <label>Serial</label>
            <input type="text" name="serial" required>

            <label>Location</label>
            <select name="location">
                <option value="Controller Office">Controller Office</option>
                <option value="CRS Office">CRS Office</option>
                <option value="IE Office">IE Office</option>
                <option value="Finance Office">Finance Office</option>
                <option value="Impex Office">Impex Office</option>
                <option value="BW HR Office">BW HR Office</option>
                <option value="Crackerjack HR Office">Crackerjack HR Office</option>
            </select>

            <label>Description</label>
            <textarea name="description" rows="3" required></textarea>
        </div>

        <!-- Column 2 -->
        <div class="form-column">
            <label>Company</label>
            <select name="company" required>
                <option value="">-- Select Company --</option>
                <option value="BW MANUFACTURING CORP.">BW MANUFACTURING CORP.</option>
                <option value="TRACKERTEER WEB DEVELOPMENT CORP.">TRACKERTEER WEB DEVELOPMENT CORP.</option>
                <option value="CRACKERJACK RECRUITMENT AGENCY CORP.">CRACKERJACK RECRUITMENT AGENCY CORP.</option>
            </select>

            <label>Department</label>
            <input type="text" name="department" required>

            <label>Section</label>
            <input type="text" name="section" required>

            <label>Employee Name</label>
            <input type="text" name="employee_name" required>

            <label>Purchase Date</label>
            <input type="date" name="purchase_date" required>

            <label>Purchase Price</label>
            <input type="number" name="purchase_price" required>

            <label>Invoice No</label>
            <input type="text" name="invoice_no" required>

            <label>SI/DR No</label>
            <input type="text" name="si_di_no" required>

            <label>Bring-In Permit</label>
            <input type="text" name="bring_in_permit" required>
        </div>

        <!-- Column 3 -->
        <div class="form-column">
            <label>Final Book Value</label>
            <input type="text" name="final_book_value" required>

            <label>Useful Life</label>
            <select name="useful_life" required>
                <option value="3 Years">3 Years</option>
                <option value="4 Years">4 Years</option>
                <option value="5 Years">5 Years</option>
            </select>

            <label>Maintenance Schedule</label>
            <input type="text" name="maintenance_schedule" required>

            <label>Last Maintenance Date</label>
            <input type="date" name="last_maintenance_date">

            <label>Status</label>
            <select name="status">
                <option value="Deployed">Deployed</option>
                <option value="Under Maintenance">Under Maintenance</option>
                <option value="Unissued">Unissued</option>
                <option value="Non-Salvageable">Non-Salvageable</option>
            </select>

            <button type="submit" class="submit-button">Enter</button>
        </div>
    </form>

    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 20px;
}

h2 {
    text-align: center;
}

.form-grid {
    display: flex;
    gap: 20px;
    justify-content: center;
    align-items: flex-start;
}

.form-column {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 300px;
}



input[type="text"],
input[type="number"],
input[type="date"],
select,
textarea {
    padding: 6px;
    font-size: 14px;
    width: 100%;
    box-sizing: border-box;
}

textarea {
    resize: vertical;
}

.submit-button {
    margin-top: 20px;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

.submit-button:hover {
    background-color: #45a049;
}

    </style>
</body>
</html>

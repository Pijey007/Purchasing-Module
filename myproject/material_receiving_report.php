<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_mrr'])) {
    $po_id = isset($_POST['po_id']) ? $_POST['po_id'] : '';

    echo "<!-- Debug: Processing PO ID: " . $po_id . " -->";

    $check_stmt = $conn->prepare("SELECT status FROM purchase_orders WHERE id = ?");
    if (!$check_stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $check_stmt->bind_param("i", $po_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $po_data = $result->fetch_assoc();
    $check_stmt->close();

    echo "<!-- Debug: PO Data: " . print_r($po_data, true) . " -->";

    if (!$po_data) {
        die("Purchase Order not found!");
    }

    if ($po_data['status'] === 'Closed') {
        die("This PO is already closed. No further deliveries can be received.");
    }

    $mrr_no = $_POST['mrr_no'];
    $received_date = $_POST['received_date'];
    $received_by = $_POST['received_by'];
    $dr_no = $_POST['dr_no'];
    $dr_personel = $_POST['dr_personel'];
    $si_no = $_POST['si_no'];

    // File upload paths
    $si_document_path = null;
    $dr_document_path = null;

    $base_upload_dir = 'uploads/mrr_docs/';
    if (!is_dir($base_upload_dir)) {
        mkdir($base_upload_dir, 0777, true);
    }

    // SI Document
    if (isset($_FILES['siDocs']) && $_FILES['siDocs']['error'] == UPLOAD_ERR_OK) {
        $si_name = time() . '_SI_' . basename($_FILES['siDocs']['name']);
        $si_target = $base_upload_dir . $si_name;
        if (move_uploaded_file($_FILES['siDocs']['tmp_name'], $si_target)) {
            $si_document_path = $si_target;
        } else {
            die("Failed to upload SI document.");
        }
    }

    // DR Document
    if (isset($_FILES['drDocs']) && $_FILES['drDocs']['error'] == UPLOAD_ERR_OK) {
        $dr_name = time() . '_DR_' . basename($_FILES['drDocs']['name']);
        $dr_target = $base_upload_dir . $dr_name;
        if (move_uploaded_file($_FILES['drDocs']['tmp_name'], $dr_target)) {
            $dr_document_path = $dr_target;
        } else {
            die("Failed to upload DR document.");
        }
    }

    // Insert into mrr table
    $stmt = $conn->prepare("INSERT INTO mrr (po_id, mrr_no, received_date, received_by, si_no, si_document, dr_no, dr_personel, dr_docs) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("issssssss", $po_id, $mrr_no, $received_date, $received_by, $si_no, $si_document_path, $dr_no, $dr_personel, $dr_document_path);
    $stmt->execute();
    $mrr_id = $stmt->insert_id;
    $stmt->close();

    // Loop through items
    if (isset($_POST['item_id']) && is_array($_POST['item_id'])) {
        foreach ($_POST['item_id'] as $index => $item_id) {
            $delivered_qty = $_POST['received_qty'][$index];
            $remark = $_POST['remarks'][$index];
            $unit_cost = 0;
            
            // MRR Transactions
            $stmt = $conn->prepare("INSERT INTO mrr_transactions (mrr_id, item_id, received_qty, remarks) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiis", $mrr_id, $item_id, $delivered_qty, $remark);
            $stmt->execute();
            $stmt->close();

            // Inventory Stock
            $now = date('Y-m-d H:i:s');
            $stmt = $conn->prepare("INSERT INTO inventory_stock (item_id, quantity_received, unit_cost, transaction_date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iids", $item_id, $delivered_qty, $unit_cost, $now);
            $stmt->execute();
            $stmt->close();

            // Update PO item status
            $stmt = $conn->prepare("SELECT quantity FROM purchase_order_items WHERE id = ?");
            $stmt->bind_param("i", $item_id);
            $stmt->execute();
            $stmt->bind_result($quantity_ordered);
            $stmt->fetch();
            $stmt->close();

            $stmt = $conn->prepare("SELECT SUM(received_qty) FROM mrr_transactions WHERE item_id = ?");
            $stmt->bind_param("i", $item_id);
            $stmt->execute();
            $stmt->bind_result($total_received);
            $stmt->fetch();
            $stmt->close();

            if ($total_received >= $quantity_ordered) {
                $stmt = $conn->prepare("UPDATE purchase_order_items SET status = 'Closed' WHERE id = ?");
            } elseif ($total_received > 0) {
                $stmt = $conn->prepare("UPDATE purchase_order_items SET status = 'Partial' WHERE id = ?");
            } else {
                $stmt = $conn->prepare("UPDATE purchase_order_items SET status = 'Open' WHERE id = ?");
            }
            $stmt->bind_param("i", $item_id);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Auto-close PO if all items closed
    $stmt = $conn->prepare("SELECT COUNT(*) FROM purchase_order_items WHERE po_id = ? AND status != 'Closed'");
    $stmt->bind_param("i", $po_id);
    $stmt->execute();
    $stmt->bind_result($open_items_count);
    $stmt->fetch();
    $stmt->close();

    if ($open_items_count == 0) {
        $stmt = $conn->prepare("UPDATE purchase_orders SET status = 'Closed' WHERE id = ?");
        $stmt->bind_param("i", $po_id);
        $stmt->execute();
        $stmt->close();
    }

    echo "<script>alert('Material Receiving Report saved successfully!'); window.location.href='material_receiving_report.php';</script>";
}

// For loading form
$po_id = isset($_GET['po_id']) ? $_GET['po_id'] : '';
$po_info = null;
$po_items = [];
$all_items_closed = false;

if ($po_id) {
    $stmt = $conn->prepare("SELECT * FROM purchase_orders WHERE id = ?");
    $stmt->bind_param("i", $po_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $po_info = $result->fetch_assoc();
    $stmt->close();

    if ($po_info) {
        $stmt = $conn->prepare("SELECT * FROM purchase_order_items WHERE po_id = ?");
        $stmt->bind_param("i", $po_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $po_items[] = $row;
        }
        $stmt->close();

        // Check if all items are closed
        $all_items_closed = true;
        foreach ($po_items as $item) {
            if ($item['status'] !== 'Closed') {
                $all_items_closed = false;
                break;
            }
        }
    } else {
        echo "<script>alert('Purchase Order not found!'); window.location.href='material_receiving_report.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="myproject/css/material_receiving.css">
    <title>Purchasing System - Material Receiving Report</title>
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
    <?php if ($all_items_closed): ?>
        <p style="color:red;"><strong>All items in this PO are fully delivered. No further submissions allowed.</strong></p>
    <?php else: ?>
        <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <input type="hidden" name="po_id" value="<?= $po_id ?>">
            <input type="hidden" name="po_status" value="<?= $po_info['status'] ?>">
            <label>MRR No:</label>
            <input type="text" name="mrr_no" required>
            <label>Received Date:</label>
            <input type="date" name="received_date" required>
            <label>Received By:</label>
            <input type="text" name="received_by" required>
            <label>Sales/Service No:</label>
            <input type="text" name="si_no" required>
            <label>DR NO:</label>
            <input type="text" name="dr_no" required>
            <label>DR Personel</label>
            <input type="text" name="dr_personel" required>
            <label for="drDocs">DR Document</label>
            <input type="file" id="drDocs" name="drDocs">
            <label for="siDocs">SI Document</label>
            <input type="file" id="siDocs" name="siDocs">

            <h3>Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Description</th>
                        <th>Ordered Qty</th>
                        <th>Received Qty</th>
                        <th>Remarks</th>
                        <th>Status</th>
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
                        <td><?= htmlspecialchars($item['status']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" name="submit_mrr">Submit MRR</button>
        </form>
    <?php endif; ?>
<?php endif; ?>

<hr>
<h3>Closed Purchase Orders</h3>
<table border="1">
    <thead>
        <tr>
            <th>PO Number</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $closed_pos = $conn->query("SELECT id, po_no FROM purchase_orders WHERE status = 'Closed'");
        while ($row = $closed_pos->fetch_assoc()):
        ?>
        <tr>
            <td><?= htmlspecialchars($row['po_no']) ?></td>
            <td>Closed</td>
            <td><a href="view_po_history.php?po_id=<?= $row['id'] ?>">View MRR Transactions</a></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<div class="return"><a href="dashboard.php">← Back to Dashboard</a></div>

<script>
function validateForm() {
    var poStatus = document.getElementsByName('po_status')[0].value;
    if (poStatus === 'Closed') {
        alert('This PO is already closed. No further deliveries can be received.');
        return false;
    }
    return true;
}
</script>
</body>
</html>

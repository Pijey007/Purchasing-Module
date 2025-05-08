<?php
include 'db.php';
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect main Purchase Order data
    $company = $_POST['company'];
    $address = $_POST['address'];
    $contact_no = $_POST['contact_no'];
    $email_address = $_POST['email_address'];
    $tin_no = $_POST['tin_no'];
    $attention = $_POST['attention'];
    $po_no = $_POST['po_no'];
    $order_date = $_POST['date'];
    $mrf = $_POST['mrf'];
    $terms = $_POST['terms'];
    $incoterms = $_POST['incoterms'];
    $ship_mode = $_POST['ship_mode'];
    $delivery_date = $_POST['delivery_date'];
    $grand_total = floatval($_POST['grand_total']);
    $prepared_by = $_POST['prepared_by'];
    $approved_by_dept = $_POST['approved_by_dept'];
    $received_by = $_POST['received_by'];
    $approved_by_purchasing = $_POST['approved_by_purchasing'];

    // Insert into purchase_orders
    $order_sql = "INSERT INTO purchase_orders (
        company, address, contact_no, email_address, tin_no, attention, po_no, order_date, mrf, terms, incoterms, ship_mode, delivery_date, grand_total, 
        prepared_by, approved_by_dept, received_by, approved_by_purchasing
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($order_sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "sssssssssssssdssss",
        $company, $address, $contact_no, $email_address, $tin_no, $attention, $po_no, $order_date,
        $mrf, $terms, $incoterms, $ship_mode, $delivery_date, $grand_total,
        $prepared_by, $approved_by_dept, $received_by, $approved_by_purchasing
    );

    if ($stmt->execute()) {
        $po_id = $stmt->insert_id;
        $stmt->close();

        // Insert items
        $item_sql = "INSERT INTO purchase_order_items 
            (po_id, item, code, description, quantity, uom, unit_price, total) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $item_stmt = $conn->prepare($item_sql);
        if (!$item_stmt) {
            die("Item Prepare failed: " . $conn->error);
        }

        // Expecting multiple input arrays like item[], code[], description[], etc.
        foreach ($_POST['item'] as $i => $item_name) {
            $item_stmt->bind_param(
                "isssissd",
                $po_id,
                $item_name,
                $_POST['code'][$i],
                $_POST['description'][$i],
                $_POST['quantity'][$i],
                $_POST['uom'][$i],
                $_POST['unit_price'][$i],
                $_POST['total'][$i]
            );
            $item_stmt->execute();
        }

        $item_stmt->close();
        $success = true;
    }
}

if ($success) {
    echo "<script>alert('Purchase Order Submitted Successfully!'); window.location.href = 'purchase_order.php';</script>";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="myproject/css/purchase_order.css">
    <title>Purchasing System - Purchase Order</title>
</head>
<body>
<div class="container">
    <div class="company-header" id="companyNameDisplay"></div>
    <h2>Purchase Order</h2>

    <form id="itemForm" method="POST">
        <label for="company">Company</label>
        <select name="company" id="company" onchange="updateCompanyHeader()" required>
            <option value="">-- Select Company --</option>
            <option value="BW MANUFACTURING CORP.">BW MANUFACTURING CORP.</option>
            <option value="TRACKERTEER WEB DEVELOPMENT CORP.">TRACKERTEER WEB DEVELOPMENT CORP.</option>
            <option value="CRACKERJACK RECRUITMENT AGENCY CORP.">CRACKERJACK RECRUITMENT AGENCY CORP.</option>
            <option value="VV MANUFACTURING CORP.">VV MANUFACTURING CORP.</option>
            <option value="GRAVYBABY PHILIPPINES CORP.">GRAVYBABY PHILIPPINES CORP.</option>
            <option value="CW MANUFACTURING CORP">CW MANUFACTURING CORP</option>
        </select>
        <div class="form-section">
            <div>
                <label for="address">Address:</label>
                <textarea name="address" id="address" rows="3" required></textarea>

                <label for="contact_no">Contact No:</label>
                <input type="text" id="contact_no" name="contact_no" required>

                <label for="email_address">E-mail Address:</label>
                <input type="text" id="email_address" name="email_address" required>
                
                <label for="tin_no">Tin No:</label>
                <input type="text" name="tin_no" id="tin_no" required>

                <label for="attention">Attention</label>
                <input type="text" name="attention" id="attention" required>

                <label for="po_no">P.0#:</label>
                <input type="text" name="po_no" id="po_no" required>
            </div>
            <div>
                <label for="date">Date:</label>
                <input type="date" name="date" id="date" required>

                <label for="mrf">MRF#:</label>
                <input type="text" name="mrf" id="mrf" required>

                <label for="terms">Terms:</label>
                <input type="text" name="terms" id="terms">

                <label for="incoterms">Incoterms</label>
                <input type="text" name="incoterms" id="incoterms" required>

                <label for="ship_mode">Ship mode</label>
                <select name="ship_mode" id="ship_mode">
                    <option value="">-- Select Ship Mode --</option>
                    <option value="sea">Sea</option>
                    <option value="air">Air</option>
                    <option value="land">Land</option>
                </select>
                
                <label for="delivery_date">Delivery Date:</label>
                <input type="date" name="delivery_date" id="delivery_date" required>
            </div>
        </div>
    
        <h3>Item/Purchase Order Table</h3>
        <table id="itemTable">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Item</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>UOM</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>

        <button type="button" class="addrow" onclick="addRow()">+ Add Item</button>

        <div style="text-align:right; margin-top: 20px;">
            <label><strong>Total Estimated Amount:</strong></label>
            <input type="text" id="grandTotal" name="grand_total" readonly
                style="font-weight: bold; width: 200px; text-align: right; background-color: #f0f0f0;">
        </div>

        <!-- <div class="accounting-reference">
            <table class="accounting-table">
                <tr>
                    <th colspan="3">For Accounting Reference</th>
                </tr>
                <tr>
                    <td>TOTAL PO AMOUNT</td>
                    <td>₱</td>
                    <td>-</td>
                </tr>
                <tr class="less-row">
                    <td>LESS EWT</td>
                    <td>₱</td>
                    <td>-</td>
                </tr>
                <tr class="less-row">
                    <td>LESS VAT</td>
                    <td>₱</td>
                    <td>-</td>
                </tr>
                <tr class="total-row">
                    <td><strong>Total</strong></td>
                    <td><strong>₱</strong></td>
                    <td>-</td>
                </tr>
            </table>
        </div> -->
        
        <div class="purchase-terms">
            <h3>Purchase Order Terms and Conditions:</h3>
            <ol>
                <li>Items to be delivered must comply with the required quality. The buyer, as the company's representative, has the right to reject items found to be defective and not in accordance with the required specification.</li>
                <li>Daily interest of 1% shall be charged on all delayed deliveries and may include cancellation of order.</li>
                <li>Processing of payments shall commence only upon submission of complete documents such as invoice and delivery receipt.</li>
                <li>Official Receipt should be issued upon payment received.</li>
                <li>Failure to meet above conditions should have a valid reason(s) to cancel this Purchase Order without prejudice to supplier's interest.</li>
                <li>The buyer reserves the right to make counter claims to the supplier should the goods supplied be found to be faulty and caused production downtime and loss, in value of money by the end user.</li>
                <li>This Purchase Order is subject to cancellation without prior notice.</li>
            </ol>
        </div>
        
        
        <div class="approval-section">
            <table class="approval-table">
                <tr>
                    <td>
                        <strong>Prepared by:</strong><br>
                        <input type="text" name="prepared_by" class="signature-line" ><br>
                        <div>Person-in-charge</div>
                    </td>
                    <td>
                        <strong>Approved by:</strong><br>
                        <input type="text" name="approved_by_dept" class="signature-line" ><br>
                        <div>Department Head</div>
                    </td>
                    <td>
                        <strong>Received by:</strong><br>
                        <input type="text" name="received_by" class="signature-line" ><br>
                        <div>Purchasing Assistant</div>
                    </td>
                    <td>
                        <strong>Approved by:</strong><br>
                        <input type="text" name="approved_by_purchasing" class="signature-line"><br>
                        <div>Purchasing Officer</div>
                    </td>
                </tr>
            </table>
        </div>
        <button type="submit" class="submit">Submit Request</button>
        <div class="return"><a href="dashboard.php">← Back to Dashboard</a></div>
    </form>
</div>
<script src="myproject/JS/purchase_order.js"></script>
</body>
</html>
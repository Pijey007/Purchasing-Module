<?php
include 'db.php';


$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company = $_POST['company'];
    $requestor_name = $_POST['requestor_name'];
    $department = $_POST['department'];
    $reason = $_POST['reason'];
    $due_date = $_POST['due_date'];
    $date = $_POST['date'];
    $section = $_POST['section'];
    $mrf = $_POST['mrf'];
    $po_number = $_POST['po_number'];
    $for_costing = isset($_POST['for_costing']) ? 1 : 0;
    $for_purchase = isset($_POST['for_purchase']) ? 1 : 0;
    $prepared_by = $_POST['prepared_by'];
    $approved_by_dept = $_POST['approved_by_dept'];
    $received_by = $_POST['received_by'];
    $approved_by_purchasing = $_POST['approved_by_purchasing'];
    $grand_total = floatval($_POST['grand_total']);

    $sql = "INSERT INTO material_request (
        company, requestor_name, department, reason, due_date, date, section, mrf, po_number, 
        for_costing, for_purchase, prepared_by, approved_by_dept, received_by, approved_by_purchasing, grand_total
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ssssssssiiissssd", $company, $requestor_name, $department, $reason, $due_date, $date, $section, $mrf, $po_number,
    $for_costing, $for_purchase, $prepared_by, $approved_by_dept, $received_by, $approved_by_purchasing, $grand_total);

    if ($stmt->execute()) {
        $request_id = $stmt->insert_id;
        $stmt->close();

        $item_sql = "INSERT INTO material_request_items 
            (request_id, item_code, description, stocks, consumption, quantity, unit_price, total_price) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $item_stmt = $conn->prepare($item_sql);
        if (!$item_stmt) {
            die("Item Prepare failed: " . $conn->error);
        }

        foreach ($_POST['item_code'] as $i => $code) {
            $item_stmt->bind_param("issiiidd", $request_id, $code, $_POST['description'][$i], $_POST['stocks'][$i], $_POST['consumption'][$i],
                $_POST['quantity'][$i], $_POST['unit_price'][$i], $_POST['total_price'][$i]);
            $item_stmt->execute();
        }

        $item_stmt->close();
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="myproject/css/material_request.css">
    <title>Purchasing System - Material Request Form</title>
</head>
<body>
    <div class="container">
        <div class="company-header" id="companyNameDisplay"></div>
        <h2>Material Request Form</h2>

        <form id="itemForm" method="POST">
            <label for="company">Company:</label>
            <select name="company" id="company" onchange="updateCompanyHeader()" required>
                <option value="">-- Select Company --</option>
                <option value="BW MANUFACTURING CORP.">BW MANUFACTURING CORP.</option>
                <option value="TRACKERTEER WEB DEVELOPMENT CORP.">TRACKERTEER WEB DEVELOPMENT CORP.</option>
                <option value="CRACKERJACK RECRUITMENT AGENCY CORPORATION">CRACKERJACK RECRUITMENT AGENCY CORPORATION</option>
                <option value="VV MANUFACTURING CORP.">VV MANUFACTURING CORP.</option>
                <option value="GRAVYBABY PHILIPPINES INC.">GRAVYBABY PHILIPPINES INC.</option>
            </select>

            <div class="form-section">
                <div>
                    <label for="requestor_name">Requestor Name:</label>
                    <input type="text" id="requestor_name" name="requestor_name" required>

                    <label for="department">Department:</label>
                    <input type="text" id="department" name="department" required>

                    <label for="reason">Reason/Purpose:</label>
                    <textarea id="reason" name="reason" rows="4"></textarea>
                </div>
                <div>
                    <label for="due_date">Due Date:</label>
                    <input type="date" id="due_date" name="due_date" required>

                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required>

                    <label for="section">Section:</label>
                    <input type="text" id="section" name="section" required>

                    <label for="mrf">MRF#:</label>
                    <input type="text" id="mrf" name="mrf" required>

                    <label for="po_number">P.O Number:</label>
                    <input type="text" id="po_number" name="po_number" required>
                </div>
            </div>

            <div class="checkbox-group">
                <label><input type="checkbox" name="for_costing" value="1"> For Costing/Budgeting</label>
                <label><input type="checkbox" name="for_purchase" value="1"> For Purchase</label>
            </div>

            <h3>Item/Material Table</h3>
            <table id="itemTable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Item/Material Code</th>
                        <th>Description</th>
                        <th>Stocks on Hand</th>
                        <th>Monthly Consumption</th>
                        <th>Quantity Needed</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>

            <button type="button" class="add-row" onclick="addRow()">+ Add Item</button>

            <div style="text-align:right; margin-top: 20px;">
                <label><strong>Total Estimated Amount:</strong></label>
                <input type="text" id="grandTotal" name="grand_total" readonly
                    style="font-weight: bold; width: 200px; text-align: right; background-color: #f0f0f0;">
            </div>

            <div class="approval-section">
                <table class="approval-table">
                    <tr>
                        <td>
                            <strong>Prepared by:</strong><br>
                            <input type="text" name="prepared_by" class="signature-line" placeholder="Person-in-charge"><br>
                            <div>Person-in-charge</div>
                        </td>
                        <td>
                            <strong>Approved by:</strong><br>
                            <input type="text" name="approved_by_dept" class="signature-line" placeholder="Department Head"><br>
                            <div>Department Head</div>
                        </td>
                        <td>
                            <strong>Received by:</strong><br>
                            <input type="text" name="received_by" class="signature-line" placeholder="Purchasing Assistant"><br>
                            <div>Purchasing Assistant</div>
                        </td>
                        <td>
                            <strong>Approved by:</strong><br>
                            <input type="text" name="approved_by_purchasing" class="signature-line" placeholder="Purchasing Officer"><br>
                            <div>Purchasing Officer</div>
                        </td>
                    </tr>
                </table>
            </div>

            <button type="submit">Submit Request</button>
            <div class="return"><a href="dashboard.php">← Back to Dashboard</a></div>
        </form>
    </div>

    <script src="myproject/JS/material_request.js"></script>

    <?php if ($success): ?>
    <script>
        alert("Material request successfully submitted!");
        window.location.href = "material_request_form.php";
    </script>
    <?php endif; ?>
</body>
</html>

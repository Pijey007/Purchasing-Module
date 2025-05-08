<?php
include 'db.php';
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") { $supplierName = isset($_POST['supplierName']) ? $_POST['supplierName'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $taxId = isset($_POST['taxId']) ? $_POST['taxId'] : '';
    $branchCode = isset($_POST['branchCode']) ? $_POST['branchCode'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $contactPerson = isset($_POST['contactPerson']) ? $_POST['contactPerson'] : '';
    $tags = isset($_POST['tags']) ? $_POST['tags'] : '';
    $paymentTerms = isset($_POST['paymentTerms']) ? $_POST['paymentTerms'] : '';
    $paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : '';
    $receiptReminder = isset($_POST['receiptReminder']) ? $_POST['receiptReminder'] : '';


  // Handle type checkboxes
    $type = '';
    if (!empty($_POST['type'])) {
        $type = is_array($_POST['type']) ? implode(', ', $_POST['type']) : $_POST['type'];
    }

$supplierCode = "SUP-" . strtoupper(uniqid());

$stmt = $conn->prepare("INSERT INTO supplier (supplier_code, supplier_name, type, address, tax_id, branch_code, phone, email, contact_person, tags, payment_terms, payment_method, receipt_reminder) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sssssssssssss", $supplierCode, $supplierName, $type, $address, $taxId, $branchCode, $phone, $email, $contactPerson, $tags, $paymentTerms, $paymentMethod, $receiptReminder);
        $stmt->execute();
        $stmt->close();
} else {
    echo "Error in preparing statement: " . $conn->error;
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="myproject/css/supplier_management.css">
    <title>Purchasing System - Supplier Management</title>
</head>
<body>

    <h2 class="header">Supplier Form</h2>
    <div class="container-3">
        <h2>Add Supplier</h2>
        <form method="post" action="">
            <div class="form-row">
                <div class="form-group">
                    <label for="supplierName">Supplier Name</label>
                    <input type="text" id="supplierName" name="supplierName" required>
                </div>
                <div class="form-group">
                    <label>Supplier Type</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="type[]" value="Individual"> Individual</label>
                        <label><input type="checkbox" name="type[]" value="Company"> Company</label>
                        <label><input type="checkbox" name="type[]" value="VAT"> VAT</label>
                        <label><input type="checkbox" name="type[]" value="NON VAT"> NON VAT</label>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label for="taxId">Tax ID</label>
                    <input type="text" name="taxId">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="branchCode">Branch Code</label>
                    <input type="text" name="branchCode">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email">
                </div>
                <div class="form-group">
                    <label for="contactPerson">Contact Person</label>
                    <input type="text" name="contactPerson">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <input type="text" name="tags">
                </div>
                <div class="form-group">
                    <label for="paymentTerms">Payment Terms</label>
                    <input type="text" name="paymentTerms">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="paymentMethod">Payment Method</label>
                    <select name="paymentMethod">
                        <option value="">-- Select --</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Cash">Cash</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="receiptReminder">Receipt Reminder</label>
                    <input type="text" name="receiptReminder">
                </div>
            </div>

            <div class="form-footer">
                <button type="submit">Add Supplier</button>
            </div>
        </form>
    </div>

    <h2>Submitted Suppliers</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Supplier Code</th>
                <th>Supplier Name</th>
                <th>Type</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Tags</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($conn)) {
                $result = $conn->query("SELECT * FROM supplier ORDER BY id DESC");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['supplier_code']}</td>
                            <td>{$row['supplier_name']}</td>
                            <td>{$row['type']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['tags']}</td>
                            <td>
                                <a href='edit_supplier.php?id={$row['id']}' class='btn-edit'>Edit</a> |
                                <a href='delete_supplier.php?id={$row['id']}' class='btn-delete' onclick='return confirm(\"Delete this supplier?\")'>Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No supplier found.</td></tr>";
                }
                $conn->close();
            }
            ?>
        </tbody>
    </table>

    <div class="return"><a href="dashboard.php">← Back to Dashboard</a></div>

    <script src="myproject/JS/supplier.js"></script>
</body>
</html>
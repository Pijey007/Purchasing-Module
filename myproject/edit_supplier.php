<?php
include 'db.php';

// Get supplier by ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$supplier = null;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM suppliers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $supplier = $result->fetch_assoc();
    $stmt->close();
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vendorName = $_POST['vendorName'];
    $type = $_POST['type'];
    $address = $_POST['address'];
    $taxId = $_POST['taxId'];
    $branchCode = $_POST['branchCode'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $contactPerson = $_POST['contactPerson'];
    $tags = $_POST['tags'];
    $paymentTerms = $_POST['paymentTerms'];
    $paymentMethod = $_POST['paymentMethod'];
    $receiptReminder = $_POST['receiptReminder'];

    $stmt = $conn->prepare("UPDATE suppliers SET supplier_name=?, type=?, address=?, tax_id=?, branch_code=?, phone=?, email=?, contact_person=?, tags=?, payment_terms=?, payment_method=?, receipt_reminder=? WHERE id=?");
    $stmt->bind_param("ssssssssssssi", $vendorName, $type, $address, $taxId, $branchCode, $phone, $email, $contactPerson, $tags, $paymentTerms, $paymentMethod, $receiptReminder, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Supplier updated successfully.'); window.location.href='supplier_management.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Supplier</title>
    <link rel="stylesheet" href="myproject/css/item.css">
    <style>
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Supplier</h2>
    <?php if ($supplier): ?>
    <form method="post">
        <div class="form-grid">
            <div class="form-group">
                <label>Vendor Name</label>
                <input type="text" name="vendorName" value="<?= htmlspecialchars($supplier['vendor_name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Type</label>
                <input type="text" name="type" value="<?= htmlspecialchars($supplier['type']) ?>">
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" value="<?= htmlspecialchars($supplier['address']) ?>">
            </div>
            <div class="form-group">
                <label>Tax ID</label>
                <input type="text" name="taxId" value="<?= htmlspecialchars($supplier['tax_id']) ?>">
            </div>
            <div class="form-group">
                <label>Branch Code</label>
                <input type="text" name="branchCode" value="<?= htmlspecialchars($supplier['branch_code']) ?>">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($supplier['phone']) ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($supplier['email']) ?>">
            </div>
            <div class="form-group">
                <label>Contact Person</label>
                <input type="text" name="contactPerson" value="<?= htmlspecialchars($supplier['contact_person']) ?>">
            </div>
            <div class="form-group">
                <label>Tags</label>
                <input type="text" name="tags" value="<?= htmlspecialchars($supplier['tags']) ?>">
            </div>
            <div class="form-group">
                <label>Payment Terms</label>
                <input type="text" name="paymentTerms" value="<?= htmlspecialchars($supplier['payment_terms']) ?>">
            </div>
            <div class="form-group">
                <label>Payment Method</label>
                <input type="text" name="paymentMethod" value="<?= htmlspecialchars($supplier['payment_method']) ?>">
            </div>
            <div class="form-group">
                <label>Receipt Reminder</label>
                <input type="text" name="receiptReminder" value="<?= htmlspecialchars($supplier['receipt_reminder']) ?>">
            </div>
        </div>
        <div style="text-align:center; margin-top: 20px; gap: 20px;">
            <button type="submit">Update Supplier</button>
            <a href="supplier_management.php" class="btn-delete" style="margin-left: 10px;">Cancel</a>
        </div>
    </form>
    <?php else: ?>
        <p style="color:red;">Supplier not found.</p>
    <?php endif; ?>
</div>
</body>
</html>

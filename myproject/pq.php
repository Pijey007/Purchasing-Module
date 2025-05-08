<!-- purchase-quotation.html -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Purchase Quotation</title>
<link rel="stylesheet" href="myproject/css/pq.css">
</head>
<body>
<div class="container">
    <h2>Purchase Quotation Form</h2>
    
    <form id="pqForm">
    <div class="form-row">
        <label for="supplier">Supplier Name:</label>
        <input type="text" id="supplier" name="supplier" required>
    </div>

    <div class="form-row">
        <label for="quotationDate">Quotation Date:</label>
        <input type="date" id="quotationDate" name="quotationDate" required>
    </div>

    <h3>Items</h3>
    <table id="itemsTable">
        <thead>
        <tr>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody id="itemsBody">
        <tr>
            <td><input type="text" name="itemName[]" required></td>
            <td><input type="number" name="quantity[]" class="qty" min="1" required></td>
            <td><input type="number" name="unitPrice[]" class="unit-price" min="0" step="0.01" required></td>
            <td><input type="text" class="total" readonly></td>
            <td><button type="button" class="remove-row">Remove</button></td>
        </tr>
        </tbody>
    </table>
    <button type="button" id="addItem">Add Item</button>

    <div class="form-row total-summary">
        <label for="grandTotal">Grand Total:</label>
        <input type="text" id="grandTotal" readonly>
    </div>

    <button type="submit">Submit Quotation</button>
    </form>
</div>
<div class="return"><a href="dashboard.php">← Back to Dashboard</a></div>

<script src="pq.js"></script>
</body>
</html>

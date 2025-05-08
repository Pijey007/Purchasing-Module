<?php
include 'db.php';
// Form submission logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemName = $_POST['itemName'];
    $quantity = $_POST['quantity'];
    $urgency = $_POST['urgency'];
    $department = $_POST['department'];

    $uploadDir = "uploads_purchase/";
    $filePath = "";

    if (isset($_FILES['supportingDocs']) && $_FILES['supportingDocs']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['supportingDocs']['name']);
        $filePath = $uploadDir . time() . "_" . $fileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        move_uploaded_file($_FILES['supportingDocs']['tmp_name'], $filePath);
    }

    $stmt = $conn->prepare("INSERT INTO purchase_requests (item_name, quantity, urgency, department, supporting_document) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $itemName, $quantity, $urgency, $department, $filePath);

    if ($stmt->execute()) {
        echo "<script>alert('Purchase request submitted successfully!'); window.location.href='purchase_request.php';</script>";
    } else {
        echo "<script>alert('Error submitting request: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchasing System - Purchase Request</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="myproject/css/purchase_request.css">
</head>
<body>
    <div class="container">
        <h2>Purchase Request Form</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-group">
                    <label for="itemName">Item Name</label>
                    <input type="text" id="itemName" name="itemName" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" required>
                </div>
                <div class="form-group">
                    <label for="urgency">Urgency</label>
                    <select id="urgency" name="urgency" required>
                        <option value="">-- Select Urgency --</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="department">Department</label>
                    <input type="text" id="department" name="department" required>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label for="supportingDocs">Attach Supporting Document (Optional)</label>
                    <input type="file" id="supportingDocs" name="supportingDocs">
                </div>
            </div>
            <button type="submit">Submit Request</button>
        </form>
    </div>

    <div class="container">
        <h2>Submitted Requests</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Urgency</th>
                    <th>Department</th>
                    <th>Document</th>
                    <th>Submitted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM purchase_requests ORDER BY submitted_at DESC");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['item_name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['urgency']}</td>
                        <td>{$row['department']}</td>
                        <td>";
                    if (!empty($row['supporting_document'])) {
                        echo "<a href='{$row['supporting_document']}' target='_blank'>View</a>";
                    } else {
                        echo "None";
                    }
                    echo "</td>
                        <td>{$row['submitted_at']}</td>
                        <td>
                            <a href='edit_purchase_request.php?id={$row['id']}' class='btn-edit'>Edit</a>
                            <a href='delete_purchase_request.php?id={$row['id']}' class='btn-delete' onclick=\"return confirm('Are you sure you want to delete this request?');\">Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="return">
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>

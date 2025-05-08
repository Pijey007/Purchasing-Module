<?php
include 'db.php';

// Fetch pending orders
$sql = "SELECT po_no, company, order_date, status
        FROM purchase_orders
        WHERE status = 'Pending'
        ORDER BY order_date DESC";



$result = $conn->query($sql);
if (!$result) {
    die("SQL Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Orders</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            padding: 30px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 15px 20px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #0066cc;
            color: white;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 12px;
            color: white;
        }
        .pending {
            background-color: #e74c3c;
        }
        .no-orders {
            text-align: center;
            margin-top: 50px;
            color: #999;
            font-size: 18px;
        }
    </style>
</head>
<body>

<h1>Pending Orders</h1>

<?php if ($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Order No.</th>
                <th>Supplier Name</th>
                <th>Order Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['po_no']); ?></td>
                <td><?php echo htmlspecialchars($row['company']); ?></td>
                <td><?php echo htmlspecialchars(date('M d, Y', strtotime($row['order_date']))); ?></td>
                <td><span class="badge pending"><?php echo htmlspecialchars($row['status']); ?></span></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="no-orders">No pending orders found.</div>
<?php endif; ?>

<?php $conn->close(); ?>

</body>
</html>

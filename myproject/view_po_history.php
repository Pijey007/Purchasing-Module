<?php
include 'db.php';

$po_id = isset($_GET['po_id']) ? intval($_GET['po_id']) : 0;

if (!$po_id) {
    die("Invalid PO ID.");
}

// Get PO Info
$po_stmt = $conn->prepare("SELECT po_no FROM purchase_orders WHERE id = ?");
$po_stmt->bind_param("i", $po_id);
$po_stmt->execute();
$po_stmt->bind_result($po_no);
$po_stmt->fetch();
$po_stmt->close();

if (!$po_no) {
    die("Purchase Order not found.");
}

// Get MRRs
$mrrs_stmt = $conn->prepare("SELECT * FROM mrr WHERE po_id = ?");
$mrrs_stmt->bind_param("i", $po_id);
$mrrs_stmt->execute();
$mrrs_result = $mrrs_stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>PO History - <?= htmlspecialchars($po_no) ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        h2, h3 { margin-top: 40px; }
        .back-link { margin-top: 20px; display: inline-block; }
    </style>
</head>
<body>
    <h2>MRR History for PO: <?= htmlspecialchars($po_no) ?></h2>

    <?php while ($mrr = $mrrs_result->fetch_assoc()): ?>
        <h3>MRR No: <?= htmlspecialchars($mrr['mrr_no']) ?></h3>
        <p><strong>Received Date:</strong> <?= $mrr['received_date'] ?></p>
        <p><strong>Received By:</strong> <?= htmlspecialchars($mrr['received_by']) ?></p>
        <p><strong>SI No:</strong> <?= htmlspecialchars($mrr['si_no']) ?> 
            <?= $mrr['si_document'] ? "- <a href='{$mrr['si_document']}' target='_blank'>View Document</a>" : '' ?>
        </p>
        <p><strong>DR No:</strong> <?= htmlspecialchars($mrr['dr_no']) ?> | 
            <strong>DR Personnel:</strong> <?= htmlspecialchars($mrr['dr_personel']) ?> 
            <?= $mrr['dr_docs'] ? "- <a href='{$mrr['dr_docs']}' target='_blank'>View Document</a>" : '' ?>
        </p>

        <!-- Get Transactions -->
        <?php
        $mrr_id = $mrr['id'];
        $tx_stmt = $conn->prepare("
            SELECT m.received_qty, m.remarks, i.item, i.description 
            FROM mrr_transactions m 
            JOIN purchase_order_items i ON m.item_id = i.id 
            WHERE m.mrr_id = ?
        ");
        $tx_stmt->bind_param("i", $mrr_id);
        $tx_stmt->execute();
        $tx_result = $tx_stmt->get_result();
        ?>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Description</th>
                    <th>Received Qty</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($tx = $tx_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($tx['item']) ?></td>
                    <td><?= htmlspecialchars($tx['description']) ?></td>
                    <td><?= $tx['received_qty'] ?></td>
                    <td><?= htmlspecialchars($tx['remarks']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endwhile; ?>

    <div class="back-link">
        <a href="material_receiving_report.php">← Back to MRR Form</a>
    </div>
</body>
</html>

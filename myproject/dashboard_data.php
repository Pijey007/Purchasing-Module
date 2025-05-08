<?php
include 'db.php';
// Get number of Purchase Requests this month
$query1 = "SELECT COUNT(*) as count FROM purchase_requests WHERE MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())";
$result1 = mysqli_query($conn, $query1);
$row1 = mysqli_fetch_assoc($result1);
$requestsThisMonth = $row1['count'];

// Get total Purchase Orders
$query2 = "SELECT COUNT(*) as count FROM purchase_orders";
$result2 = mysqli_query($conn, $query2);
$row2 = mysqli_fetch_assoc($result2);
$totalPO = $row2['count'];

// Get pending approvals
$query3 = "SELECT COUNT(*) as count FROM purchase_requests WHERE status = 'Pending'";
$result3 = mysqli_query($conn, $query3);
$row3 = mysqli_fetch_assoc($result3);
$pendingApprovals = $row3['count'];

// Get total suppliers
$query4 = "SELECT COUNT(*) as count FROM supplier";
$result4 = mysqli_query($conn, $query4);
$row4 = mysqli_fetch_assoc($result4);
$totalSuppliers = $row4['count'];

// Output JSON
echo json_encode([
    'requestsThisMonth' => $requestsThisMonth,
    'totalPO' => $totalPO,
    'pendingApprovals' => $pendingApprovals,
    'totalSuppliers' => $totalSuppliers
]);
?>

<?php
include 'db.php';
// Universal counting function
function getTableCount($conn, $table, $where = '') {
    $sql = "SELECT COUNT(*) as total FROM `$table`";
    if (!empty($where)) {
        $sql .= " WHERE $where";
    }
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    } else {
        return 0; // If query fails, return 0
    }
}

// Now get all counts super easily
$purchaseRequestCount = getTableCount($conn, 'purchase_requests');
$supplierCount = getTableCount($conn, 'supplier');
$itemCount = getTableCount($conn, 'item_file');
$pendingOrderCount = getTableCount($conn, 'purchase_orders', "status = 'Pending'");

$conn->close(); 
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Purchasing System - Dashboard</title>
  <link rel="stylesheet" href="myproject/css/nav-bar.css">
  <link rel="stylesheet" href="myproject/css/dashboard.css">
</head>
<body>
  <header class="top-navbar">
    <div class="admin-info">
      <div class="profile-dropdown">
        <img src="myproject/image/admin_profile.png" alt="Admin Profile" class="profile-pic" onclick="toggleDropdown()">
        <div class="dropdown-menu" id="profileDropdown">
          <a href="#">Profile</a>
          <a href="#">Settings</a>
          <a href="logout.php">Logout</a>
        </div>
      </div>
      <span>Welcome, PJ</span>
    </div>
  </header>
  <nav>
    <div class="logo">Purchasing System</div>
    <ul class="nav-links">
      <li><a href="purchase_request.php">Purchase Request</a></li>
      <li><a href="supplier_management.php">Supplier Management</a></li>
      <li><a href="item_file.php">Item File</a></li>
      <li><a href="pq.php">PQ</a></li>
      <li><a href="purchase_order.php">Purchase Order</a></li>
      <li><a href="material_receiving_report.php">Material Receiving Report (MRR)</a></li>
      <li><a href="#">Issuance</a></li>
      <li class="dropdown">
        <a href="#">Requisition Form ▾</a>
        <ul class="dropdown-content">
          <li><a href="material_request_form.php">Material Request Form</a></li>
          <li><a href="service_request_form.php">Service Request Form</a></li>
        </ul>
      </li>
      <li class="dropdown">
        <a href="#">Inventory ▾</a>
        <ul class="dropdown-content">
        <li class="dropdown-submenu">
          <a href="#">Assets ▸</a>
          <ul class="dropdown-content1">
            <li><a href="create_asset_card.php">Create Asset Card</a></li>
            <li><a href="asset_card_inventory.html">Asset Card Inventory</a></li>
          </ul>
        </li>
          <li><a href="production_inventory.php">Production Supplies</a></li>
          <li><a href="non_production_inventory.php">Non-Production Supplies</a></li>
        </ul>
      </li>
    </ul>
  </nav>

  <div class="content">
    <h1>Dashboard Overview</h1>

    <div class="dashboard">
      <div class="card card-requests">
      <div class="card-icon">📄</div>
      <div class="card-content">
        <h3>Purchase Requests</h3>
        <p><?php echo $purchaseRequestCount; ?> Requests</p>
        <span class="badge pending">Pending</span>
      </div>
    </div>

    <div class="card card-suppliers">
      <div class="card-icon">🏢</div>
      <div class="card-content">
        <h3>Suppliers</h3>
        <p><?php echo $supplierCount; ?> Active</p>
        <span class="badge active">Active</span>
      </div>
    </div>

    <div class="card card-items">
      <div class="card-icon">📦</div>
      <div class="card-content">
        <h3>Items</h3>
        <p><?php echo $itemCount; ?> Total</p>
        <span class="badge available">Available</span>
      </div>
    </div>

    <div class="card card-orders">
      <div class="card-icon">🧾</div>
      <div class="card-content">
        <h3><a href="pending_orders.php">Pending Orders</a></h3>
        <p><?php echo $pendingOrderCount; ?> Pending</p>
        <span class="badge warning">In Progress</span>
      </div>
    </div>
    
  </div>
</div>
<script src="myproject/JS/dashboard.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Asset Inventory</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 30px;
      background-color: #f9f9f9;
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      border-radius: 6px;
      overflow: hidden;
    }

    thead tr:first-child th {
      background-color: #2c3e50;
      color: white;
      font-size: 14px;
      padding: 10px 8px;
      text-align: center;
    }

    thead tr:nth-child(2) th {
      background-color: #34495e;
      color: white;
      font-size: 13px;
      padding: 8px;
      text-align: center;
    }

    tbody tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    tbody td {
      padding: 10px 8px;
      text-align: center;
      font-size: 13px;
      color: #333;
    }

    img.barcode {
      height: 40px;
    }

    @media (max-width: 1200px) {
      table {
        font-size: 11px;
      }
    }
  </style>
</head>
<body>

<h1>Asset Inventory</h1>

<table>
  <thead>
    <tr>
      <th colspan="3">Record Details</th>
      <th colspan="6">Asset Details</th>
      <th colspan="4">Person in Charge</th>
      <th colspan="5">Purchase Details</th>
      <th colspan="4">Depreciation Details</th>
      <th colspan="3">Status & Maintenance</th>
      <th colspan="2">Barcode</th>
    </tr>
    <tr>
      <th>Serial Card</th>
      <th>Asset ID</th>
      <th>Tag Date</th>

      <th>Asset Name</th>
      <th>Category</th>
      <th>Model</th>
      <th>Brand</th>
      <th>Serial</th>
      <th>Location / Description</th>

      <th>Employee Name</th>
      <th>Company</th>
      <th>Department</th>
      <th>Section</th>

      <th>Purchase Date</th>
      <th>Purchase Price</th>
      <th>Invoice No</th>
      <th>SI/DR No</th>
      <th>Bring-In Permit No</th>

      <th>Current Value</th>
      <th>Monthly Depreciation</th>
      <th>Useful Life</th>
      <th>Final Book Value</th>

      <th>Status</th>
      <th>Maintenance Schedule</th>
      <th>Last Maintenance</th>

      <th>Barcode Value</th>
      <th>Barcode</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>SC-001</td>
      <td>AST-1001</td>
      <td>2025-05-01</td>

      <td>Printer</td>
      <td>Office Equipment</td>
      <td>HP LaserJet</td>
      <td>HP</td>
      <td>SN123456</td>
      <td>Main Office / Printer in Admin Room</td>

      <td>John Doe</td>
      <td>ABC Corp</td>
      <td>Admin</td>
      <td>Records</td>

      <td>2023-10-15</td>
      <td>₱25,000.00</td>
      <td>INV-7890</td>
      <td>SI-456 / DR-789</td>
      <td>BIP-1122</td>

      <td>₱18,000.00</td>
      <td>₱350.00</td>
      <td>60 Months</td>
      <td>₱5,000.00</td>

      <td>In Use</td>
      <td>Quarterly</td>
      <td>2025-04-01</td>

      <td>ABC123456789</td>
      <td><img src="barcodes/ABC123456789.png" alt="Barcode" class="barcode"></td>
    </tr>
  </tbody>
</table>

</body>
</html>

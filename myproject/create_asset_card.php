<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="myproject/css/create_asset.css">
    <title>Create Asset Card</title>
</head>
<body>
<div class="container">
    <h2>Asset Card Entry Form</h2>
    <form class="form-grid">
        <div class="form-column">
            <label for="asset_name">Asset Name</label>
            <input type="text" id="asset_name" name="asset_name" required>

            <label for="category">Category</label>
            <select name="category" id="category" required>
                <option value="">-- Select Category --</option>
                <option value="Computer">Computer</option>
                <option value="Desks">Desks</option>
                <option value="Chairs">Chairs</option>
                <option value="Computer Peripheral">Computer Peripheral</option>
                <option value="Printer">Printer</option>
                <option value="Furnitures and Fiturex">Furnitures and Fiturex</option>
            </select>
            
            <label for="model">Model</label>
            <input type="text" name="model" id="model" required>

            <label for="brand">Brand</label>
            <input type="text" name="brand" id="brand" required>

            <label for="serial">Serial</label>
            <input type="text" name="serial" id="serial" required>

            <label for="location">location</label>
            <select name="location" id="location">
                <option value="Controller Office">Controller Office</option>
                <option value="CRS Office">CRS Office</option>
                <option value="IE Office">IE Office</option>
                <option value="Finance Office">Finance Office</option>
                <option value="Impex Office">Impex Office</option>
                <option value="BW HR Office">BW HR Office</option>
                <option value="Crackerjack HR Office">Crackerjack HR Office</option>
            </select>

            <label for="description">Description</label>
            <textarea name="description" id="description" rows="3" required></textarea>
        </div>
        <div class="form-column">
            <label for="company">Company</label>
            <select name="company" id="company" required>
                <option value="">-- Select Company --</option>
                <option value="BW MANUFACTURING CORP.">BW MANUFACTURING CORP.</option>
                <option value="TRACKERTEER WEB DEVELOPMENT CORP.">TRACKERTEER WEB DEVELOPMENT CORP.</option>
                <option value="CRACKERJACK RECRUITMENT AGENCY CORP.">CRACKERJACK RECRUITMENT AGENCY CORP.</option>
            </select>
            
            <label for="deparment">Department</label>
            <input type="text" name="deparment" id="deparment" required>

            <label for="section">Section</label>
            <input type="text" name="section" id="section" required>

            <label for="employee_name">Employee Name</label>
            <input type="text" name="employee_name" id="employee_name" required>

            <label for="purchase_date">Purchase Date</label>
            <input type="date" name="purchase_date" id="purchase_date" required>

            <label for="purchase_price">Purchase Price</label>
            <input type="number" name="purchase_price" id="purchase_price" required>

            <label for="invoice_no">Invoice No</label>
            <input type="text" name="invoice_no" id="invoice_no" required>

            <label for="si_di_no">SI/DR No</label>
            <input type="text" name="si_di_no" id="si_di_no" required>

            <label for="bring_in_permit">Bring-In Permit</label>
            <input type="text" name="bring_in_permit" id="bring_in_permit" required>
        </div>
        <div class="form-column">
            <label for="final_book_value">Final Book Value</label>
            <input type="text" name="final_book_value" id="final_book_value" required>

            <label for="useful_life">Useful Life</label>
            <select name="useful_life" id="useful_life" required>
                <option value="3 Years">3 Years</option>
                <option value="4 Years">4 Years</option>
                <option value="5 Years">5 Years</option>
            </select>

            <label for="maintenance_schedule">Maintenance Schedule</label>
            <input type="text" name="maintenance_schedule" id="maintenance_schedule" required>

            <label for="last_mataintence">Last Maintenance Date</label>
            <input type="text" name="last_mataintence" id="last_mataintence">

            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="Deployed">Deployed</option>
                <option value="Under Maintenance">Under Maintenace</option>
                <option value="Unissued">Unissued</option>
                <option value="Non-Salvageable">Non-Salvageable</option>
            </select>
            <button type="submit" class="submit-button">Submit</button>
        </div>
        </div>
    </form>
    <div class="return"><a href="dashboard.php">← Back to Dashboard</a></div>.
</div>
</body>
</html>
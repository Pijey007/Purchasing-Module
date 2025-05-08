function updateCompanyHeader() {
    const selected = document.getElementById("company").selectedOptions[0].text;
    document.getElementById("companyNameDisplay").textContent = selected;
}

let rowCount = 0;

function addRow() {
    rowCount++;
    const table = document.getElementById("tableBody");
    const row = document.createElement("tr");

    row.innerHTML = `
        <td>${rowCount}</td>
        <td><input type="text" name="item_code[]"></td>
        <td><input type="text" name="description[]"></td>
        <td><input type="number" name="unit_price[]" min="0" oninput="preventNegative(this); updateTotalPrice(this)"></td>
        <td><input type="text" name="total_price[]" readonly></td>
        <td><button type="button" onclick="removeRow(this)">Delete</button></td>
    `;

    table.appendChild(row);
}

function preventNegative(input) {
    if (parseFloat(input.value) < 0) input.value = 0;
}

function updateTotalPrice(input) {
    const row = input.closest('tr');
    const unit = parseFloat(row.querySelector('[name="unit_price[]"]').value) || 0;
    row.querySelector('[name="total_price[]"]').value = unit.toFixed(2); // Total = Unit Price only
    calculateGrandTotal();
}

function calculateGrandTotal() {
    let total = 0;
    document.querySelectorAll('[name="total_price[]"]').forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    document.getElementById("grandTotal").value = total.toFixed(2);
}

function removeRow(btn) {
    btn.closest('tr').remove();
    rowCount--;
    recalculateRowNumbers();
    calculateGrandTotal();
}

function recalculateRowNumbers() {
    document.querySelectorAll("#tableBody tr").forEach((row, i) => {
        row.children[0].textContent = i + 1;
    });
}

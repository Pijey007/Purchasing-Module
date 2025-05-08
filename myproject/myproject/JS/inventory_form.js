function filterTable() {
    const input = document.getElementById("search").value.toUpperCase();
    const rows = document.querySelector("#inventoryTable tbody").rows;

    for (let row of rows) {
        const itemCodeCell = row.cells[1].textContent.toUpperCase();
        row.style.display = itemCodeCell.indexOf(input) > -1 ? "" : "none";
    }
}
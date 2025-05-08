document.addEventListener('DOMContentLoaded', () => {
    const addItemBtn = document.getElementById('addItem');
    const itemsBody = document.getElementById('itemsBody');
    const grandTotal = document.getElementById('grandTotal');
  
    function updateTotals() {
      let totalSum = 0;
      document.querySelectorAll('#itemsBody tr').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty')?.value || 0);
        const price = parseFloat(row.querySelector('.unit-price')?.value || 0);
        const total = qty * price;
        row.querySelector('.total').value = total.toFixed(2);
        totalSum += total;
      });
      grandTotal.value = totalSum.toFixed(2);
    }
  
    addItemBtn.addEventListener('click', () => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td><input type="text" name="itemName[]" required></td>
        <td><input type="number" name="quantity[]" class="qty" min="1" required></td>
        <td><input type="number" name="unitPrice[]" class="unit-price" min="0" step="0.01" required></td>
        <td><input type="text" class="total" readonly></td>
        <td><button type="button" class="remove-row">Remove</button></td>
      `;
      itemsBody.appendChild(row);
    });
  
    itemsBody.addEventListener('input', updateTotals);
  
    itemsBody.addEventListener('click', (e) => {
      if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
        updateTotals();
      }
    });
  
    document.getElementById('pqForm').addEventListener('submit', (e) => {
      e.preventDefault();
      alert('Purchase Quotation Submitted!');
      // You can send the data to your PHP backend using fetch or form submit.
    });
  });
  
// Optional JS: prevent multiple checkboxes if only one product type is allowed
const checkboxes = document.querySelectorAll('input[name="product_type[]"]');
checkboxes.forEach((cb) => {
cb.addEventListener('change', () => {
    if (cb.checked) {
    checkboxes.forEach(other => {
        if (other !== cb) other.checked = false;
    });
    }
});
});
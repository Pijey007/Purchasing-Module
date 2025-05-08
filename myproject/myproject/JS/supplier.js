document.getElementById('vendorForm').addEventListener('submit', async function (e) {
  e.preventDefault();

  const form = this;
  const formData = new FormData(form);

  // Get checked 'type' value (Individual or Company)
  const checkedTypes = Array.from(document.querySelectorAll('input[name="type"]:checked'));
  if (checkedTypes.length !== 1) {
    alert("Please select only one type: Individual or Company.");
    return;
  }
  formData.set('type', checkedTypes[0].value);

  try {
    const response = await fetch('http://localhost/myproject/add_vendor.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams(formData),
    });

    const text = await response.text();
    console.log("Server Response:", text); // Keep for debugging

    if (response.ok && text.toLowerCase().includes("success")) {
      // Optional: Extract vendor code from response
      const match = text.match(/Code:\s*(VEND\d+)/i);
      const vendorCode = match ? match[1] : "Generated";

      // Clear form
      form.reset();

      // Manually uncheck checkboxes (reset won't do it)
      checkedTypes.forEach(cb => cb.checked = false);

      // Show success banner with vendor code
      const banner = document.getElementById('successBanner');
      document.getElementById('savedCode').innerText = vendorCode;
      banner.style.display = 'block';

      // Auto-hide banner after 3 seconds
      setTimeout(() => {
        banner.style.display = 'none';
      }, 3000);
    } else {
      alert("Failed to submit vendor data.\n" + text);
    }

  } catch (error) {
    console.error("Error submitting vendor:", error);
    alert("An error occurred while submitting the form. Please try again.");
  }
});
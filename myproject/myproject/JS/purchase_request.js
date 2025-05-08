document.getElementById("purchaseRequestForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const form = document.getElementById("purchaseRequestForm");
    const formData = new FormData(form);

    fetch("php/submit_purchase_request.php", {
    method: "POST",
    body: formData
    })
    .then(res => res.text())
    .then(data => {
    if (data === "success") {
        alert("Purchase request submitted successfully!");
        form.reset();
    } else {
        alert("Failed to submit: " + data);
    }
    })
    .catch(error => {
    console.error("Error:", error);
    alert("Something went wrong.");
    });
});

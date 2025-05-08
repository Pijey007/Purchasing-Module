function toggleDropdown() {
document.getElementById("profileDropdown").classList.toggle("show");
}

// Close dropdown if user clicks outside
window.onclick = function(event) {
    if (!event.target.matches('.profile-pic')) {
        var dropdowns = document.getElementsByClassName("dropdown-menu");
        for (var i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
        }
        }
    }
}

/*-----------------------*/
async function fetchDashboardData() {
    try {
        const response = await fetch('dashboard_data.php');
        const data = await response.json();

        document.getElementById('requestsThisMonth').innerText = data.requestsThisMonth;
        document.getElementById('totalPO').innerText = data.totalPO;
        document.getElementById('pendingApprovals').innerText = data.pendingApprovals;
        document.getElementById('totalSuppliers').innerText = data.totalSuppliers;
    } catch (error) {
        console.error('Error fetching dashboard data:', error);
    }
}

fetchDashboardData();
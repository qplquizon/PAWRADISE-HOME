import './bootstrap';

function confirmLogout() {
    if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php";
    }
}

window.confirmLogout = confirmLogout; 

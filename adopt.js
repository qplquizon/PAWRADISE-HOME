import './bootstrap';

function confirmLogout() {
    if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php";
    }
}

window.confirmLogout = confirmLogout;

// Adoption form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.adoption-form form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous warnings
            clearWarnings();
            
            // Get form fields
            const firstName = document.getElementById('firstName');
            const lastName = document.getElementById('lastName');
            const email = document.getElementById('email');
            const phone = document.getElementById('phone');
            const address = document.getElementById('address');
            
            let isValid = true;
            const requiredFields = [firstName, lastName, email, phone, address];
            
            // Check required fields
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    showWarning(field, 'This field is required.');
                    isValid = false;
                }
            });
            
            // Email validation
            if (email.value.trim() && !isValidEmail(email.value)) {
                showWarning(email, 'Please enter a valid email address.');
                isValid = false;
            }
            
            // Phone validation (basic)
            if (phone.value.trim() && !isValidPhone(phone.value)) {
                showWarning(phone, 'Please enter a valid phone number.');
                isValid = false;
            }
            
            if (isValid) {
                // Submit the form
                form.submit();
            }
        });
    }
});

function showWarning(field, message) {
    // Add warning class to field
    field.classList.add('is-invalid');
    
    // Create warning message
    const warning = document.createElement('div');
    warning.className = 'invalid-feedback';
    warning.textContent = message;
    
    // Insert after field
    field.parentNode.insertBefore(warning, field.nextSibling);
}

function clearWarnings() {
    // Remove all invalid classes and warnings
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidPhone(phone) {
    // Basic phone validation: allow digits, spaces, dashes, parentheses, + sign
    const phoneRegex = /^[\+]?[\d\s\-\(\)]+$/;
    return phoneRegex.test(phone) && phone.replace(/[^\d]/g, '').length >= 10;
}

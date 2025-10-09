# TODO for Donations Page Implementation

- [x] Update donate.php to remove existing PHP backend code for donation submission
- [x] Add donation form HTML with fields: Name, Contact Number, Amount, Payment Method (Gcash/PayPal), Reference Number
- [x] Add client-side JavaScript validation to check all fields are filled before submission
- [x] Display red warning messages if any field is missing on submit
- [x] Add placeholders for QR codes (empty img src) for Gcash and PayPal, shown based on payment method selection
- [x] Set form action to admin_panel.php with POST method
- [x] Test the form validation and submission (user will test)
- [x] Add code in admin_panel.php to handle donation form submission and insert into donations table
- [x] Add donations section in admin_panel.php to display all donations and total summary

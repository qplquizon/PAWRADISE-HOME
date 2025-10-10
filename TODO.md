# Registration Validation Task

## Steps to Complete:

1. **Update PHP Server-Side Validation in register.php**
   - Add email format validation using filter_var(FILTER_VALIDATE_EMAIL).
   - Add password length check (minimum 8 characters).
   - Simplify password hashing: Remove md5 and use password_hash directly on the plain password.
   - Ensure error messages are added to $message array if validations fail.

2. **Add Client-Side JavaScript Validation in register.php**
   - Add onsubmit="return validateForm()" to the form tag.
   - Implement validateForm() function to check email regex and password length >=8.
   - Display alerts for invalid inputs and prevent form submission if invalid.

3. **Testing**
   - Test invalid email format (e.g., "invalid-email").
   - Test short password (e.g., "1234567").
   - Test password mismatch.
   - Test valid registration.
   - Verify error messages display and successful registration works.

Progress: None completed yet.

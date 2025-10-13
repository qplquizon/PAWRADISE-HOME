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

# Admin Panel Users Table Task

## Steps to Complete:

1. **Add Query to Fetch Registered Users**
   - In admin_panel.php, add a new query to fetch all registered users' names and emails from the `account` table.
   - Store the results in a variable like $users.

2. **Add HTML Table in Statistics Tab**
   - In the "Statistics" tab, below the row of cards, add a heading "Registered Users".
   - Add a Bootstrap table displaying the names and emails.
   - Ensure the table is responsive and styled consistently.

3. **Add Search Functionality**
   - Add a search input field above the table for filtering users by name or email.
   - Implement real-time filtering using JavaScript as the user types.

4. **Testing**
   - Test the admin panel to ensure the table displays correctly.
   - Verify data is fetched without errors and sanitized properly.
   - Test the search functionality to filter users in real-time.

Progress: All steps completed.

# Add Search to Donation Tab Task

## Steps to Complete:

1. **Add Search Input in Donation Tab**
   - Add a search input field above the donations cards for filtering by name, contact number, or amount.
   - Ensure the input is Bootstrap-styled and positioned appropriately.

2. **Implement JavaScript Filtering**
   - Add JavaScript to filter donation cards in real-time as the user types.
   - Filter based on name, contact number, or amount (case-insensitive).

3. **Testing**
   - Test the donation tab to ensure the search bar appears correctly.
   - Verify real-time filtering works for various search terms.
   - Check edge cases like no matches or clearing the search.

Progress: All steps completed.

# Add Donors Table in Donation Tab Task

## Steps to Complete:

1. **Add Query to Fetch Donors**
   - Add a query to fetch donor names, contact numbers, and amounts from the `donations` table.
   - Store the results in a variable like $donors.

2. **Add HTML Table in Donation Tab**
   - Add a heading "Donors" below the cards.
   - Add a Bootstrap table displaying the donor names, contact numbers, and amounts.
   - Ensure the table is responsive and styled consistently.

3. **Testing**
   - Test the donation tab to ensure the table displays correctly.
   - Verify data is fetched without errors and sanitized properly.

Progress: All steps completed.

# Online Computer Shop - Task 1 (Viva Simple Fixed Version)

This project is written in a simple, manual and beginner-friendly PHP style so the code can be followed easily during viva/project defense.

## Task 1 Features

- Register as Customer or Admin
- Login and Logout
- Password hashing with `password_hash()`
- Password checking with `password_verify()`
- Remember Me for 30 days without changing the database
- Session login using `user_id`, `name` and `role`
- Profile page
- Update name and email
- Upload profile picture
- Change password
- Home page
- Dynamic category bar
- Category product page
- Latest 6 featured products
- AJAX email availability check
- AJAX Featured Components refresh
- Prepared SQL statements
- Output protection using `htmlspecialchars()`
- Responsive CSS

## Simple Project Flow

```text
index.php
   ↓
Controller
   ↓
Model
   ↓
MySQL Database
   ↓
View
```

There is no custom router, namespace, base controller, dependency injection or complicated framework code.

## Database Connection

Open:

```text
config/database.php
```

Normal XAMPP settings are already written:

```php
$host = 'localhost';
$databaseName = 'onlinecomputershop';
$username = 'root';
$password = '';
```

If your MySQL root account has a password, change only `$password`.

## Run the Project

1. Put `OnlineComputerShop_Task1_VIVA_SIMPLE_FIXED` inside `C:\xampp\htdocs\`.
2. Start Apache and MySQL from XAMPP.
3. Create the database `onlinecomputershop`.
4. Import `database/onlinecomputershop.sql`.
5. You may import `database/demo_data.sql` for sample data.
6. Open:

```text
http://localhost/OnlineComputerShop_Task1_VIVA_SIMPLE_FIXED/
```

## Important Button Flows for Viva

### Register

```text
Register button
→ index.php?page=register
→ registerController()
→ addUser()
→ users table
→ Login page
```

### Login

```text
Login button
→ index.php?page=login
→ loginController()
→ getUserByEmail()
→ password_verify()
→ session created
→ Home page
```

### Update Profile

```text
Update Profile button
→ index.php?page=profile
→ profileController()
→ updateUserProfile()
→ users table
→ Profile page
```

### Change Password

```text
Change Password button
→ profileController()
→ password_verify()
→ password_hash()
→ updateUserPassword()
```

### Category

```text
Category link
→ index.php?page=category&id=...
→ categoryController()
→ getProductsByCategory()
→ Category page
```

### AJAX Email Check

```text
Leave Email field
→ checkEmail() in app.js
→ ajax/check_email.php
→ emailAlreadyExists()
→ JSON response
→ message appears under Email
```

### AJAX Featured Products Refresh

```text
Refresh button
→ loadFeaturedProducts() in app.js
→ ajax/featured_products.php
→ getLatestSixProducts()
→ JSON response
→ productList is replaced
→ visible refreshed time is shown
```

Even if the same six products are still the latest six products, the page now shows a message such as:

```text
Products refreshed successfully. Time: 22:30:15
```

This makes it clear that AJAX worked without reloading the full page.

## Database Rule

The original database schema is kept unchanged. No new table or column is added.

The `users` table has no `remember_token` field, so Remember Me uses a signed cookie without changing the shared database.

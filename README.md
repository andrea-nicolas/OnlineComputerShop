<<<<<<< HEAD
# OnlineComputerShop
=======
# LabTask02 — Online Computer Shop (Task 4)

Project 02, Group 02 — Web Technologies (CSC 3215)

**Task 4:** Customer Reviews · Order Placement with Payment Method · Admin Removal of Customers & Reviews

---

## Setup

1. Start **Apache** and **MySQL** from the XAMPP control panel.
2. Open <http://localhost/phpmyadmin> and create a database named `onlinecomputershop`.
3. Import `sql/onlinecomputershop.sql` (the shared group schema).
4. Import `sql/seed_data.sql` (demo categories, brands, products, users, reviews, cart rows).
   Later on, `sql/reset_demo.sql` puts this starting state back at any time.
5. Open <http://localhost/LabTask02/>.

### Test accounts

| Role | Name | Email | Password |
|------|------|-------|----------|
| Admin | Mahmud Hasan | admin@shop.com | admin123 |
| Customer | Rafid Islam | rafid@example.com | password123 |
| Customer | Nusrat Jahan | nusrat@example.com | password123 |
| Customer | Tanvir Ahmed | tanvir@example.com | password123 |

Opening the site signs you in through **`view/login.php`**. Registration and the
password form are Task 1, so until those are merged the page lets you pick one of
the seeded customer accounts. Everything after that point is a real PHP session:

- `session_start()` runs at the top of `control/bootstrap.php`, before any output
- the session keys are the ones the project brief names &mdash; `$_SESSION["user_id"]`,
  `$_SESSION["name"]`, `$_SESSION["role"]` &mdash; so Task 1's login can set exactly
  these and every page here keeps working unchanged
- **Remember me** writes a cookie with `setcookie(..., time() + (86400 * 30), "/")`,
  and `control/bootstrap.php` uses it to rebuild the session after the browser is
  closed
- **Sign out** calls `session_unset()` and `session_destroy()`, then deletes the
  cookie by giving it `time() - 3600`, as the Session/Cookie lecture does

The cookie carries the user id plus a signature of that id, so editing it by hand
does not let anyone become another user. The brief asks for a hashed token in
`users.remember_token`, but the shared schema has no such column and we must not
alter it &mdash; adding that column is Task 1's to do.

Cart, checkout and My Orders redirect a signed-out visitor to the sign in page.
Browsing products and reading reviews stays public.

### Putting the demo back to its starting state

Placing an order empties the cart, and deleting a customer is permanent. To get
everything back, import **`sql/reset_demo.sql`** in phpMyAdmin. It empties the
eight tables and reloads the original products, accounts, reviews, cart items and
sample order. Safe to run as often as you like, and it never changes the table
structure.

---

## Folder layout

```
LabTask02/
├── control/                 request handling and server-side validation
├── model/  config.php     settings and shared helper functions
│        db.php         one mydb class holding every query, the same way
│                       the lab sample does. All prepared statements.
├── view/                    pages, with view/partials/ for header and footer
├── css/style.css
├── js/                      client-side validation
├── sql/                     shared schema + demo data
└── images/, uploads/
```

The structure follows the lab sample: `control/`, `model/`, `view/` and nothing
else. Each database function is a plain function inside `class mydb` that takes
`$conn` as its first parameter, and the control files call it like the sample's
login control does:

```php
$db     = new mydb();
$conn   = $db->openConn();
$result = $db->getReviewsByProduct($conn, $productId);

while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}
```

### SQL style

The queries follow Week 06 (PHP and MySQL): build the string in `$qry`, run it
with `$conn->query($qry)`, check with `if($res)`.

```php
$qry = "SELECT COUNT(*) AS total FROM reviews";
$res = $conn->query($qry);
```

Where a value comes from the user, the query uses a `?` placeholder and
`bind_param()` instead of pasting the value into the string:

```php
$qry  = "SELECT * FROM reviews WHERE product_id = ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$res  = $stmt->get_result();
```

This is what Week 06 theory slide 6 asks for: *"Prepared Statements protect from
SQL injection, and are very important for web application security."* It is also
grading criterion 1. Of the 32 queries, 9 have no user input and run the plain
way; the other 23 use placeholders.

`mysqli_report(MYSQLI_REPORT_OFF)` is set once at the top of `model/db.php`. PHP 8
otherwise makes mysqli throw an error object instead of returning false, which
would stop the `if($res)` checks taught in class from ever running.

---

## MVC

| Layer | Folder | What lives there |
|-------|--------|------------------|
| **Model** | `model/` | `db.php` &mdash; every query, one function each. `config.php` &mdash; settings and shared helpers. Nothing here prints anything. |
| **View** | `view/` | The pages, plus `view/partials/` for the header and footer. They display what the controller prepared and do not query the database. |
| **Controller** | `control/` | Request handling, validation and business rules. Each view includes its controller on the first line, the way the lab sample does. |

`control/bootstrap.php` is included by every controller: it starts the session,
opens one connection and works out who is signed in.

## AJAX and JSON

Two endpoints answer with JSON instead of a page. Both set
`Content-Type: application/json` and finish with `json_encode()`.

| Endpoint | Actions | Browser side |
|----------|---------|--------------|
| `control/api_reviews.php` | `list`, `add`, `delete` | `js/review_ajax.js` &mdash; **jQuery** (`$.post`, `$(document).ready`, selectors) |
| `control/api_cart.php` | `update`, `remove` | `js/cart_ajax.js` &mdash; **XMLHttpRequest** with `readyState == 4 && status == 200`, as the AJAX lecture and the lab sample write it |

Both styles are used on purpose, so the project shows the plain browser object as
taught in class as well as the jQuery version.

**Both pages still work with JavaScript switched off.** PHP draws the review list
and the cart on the first load, and the forms post normally to
`control/product_control.php` and `control/cart_control.php` if the AJAX never
runs. The endpoints repeat every check anyway &mdash; JavaScript can be edited by
the person using the page, so it never decides what is allowed.

jQuery is kept in `js/jquery.min.js` rather than loaded from a CDN, so the site
works without an internet connection.

---

## Progress

| # | Feature | Status |
|---|---------|--------|
| 1 | Review section: list, post, delete own | Done |
| 2 | Order placement with payment method | Done |
| 3 | Admin: remove customers | Done |
| 4 | Admin: remove any review | Done |
| 5 | Admin dashboard: recent orders and reviews | Done |

The cart is fully working on its own (add, change quantity, remove, checkout), so
the whole flow can be demonstrated without waiting for Task 3. When Task 3 is
merged, their cart page and product browsing replace these.

### The admin side

Signing in as **Mahmud Hasan (admin@shop.com)** on the sign in page switches the
navbar to Dashboard, Customers and Reviews.

| Page | What it does |
|------|--------------|
| `view/admin_dashboard.php` | Four counts, the latest orders and the latest reviews |
| `view/admin_customers.php` | Every customer, with a Delete button |
| `view/admin_reviews.php` | Every review, with a Delete button |

Every admin page begins with the same gate: if `$isAdmin` is false it prints a
message and stops, so a customer who types the address in gets nowhere.

Deleting a customer is the interesting one. `orders.user_id` has no
`ON DELETE CASCADE`, so a plain `DELETE FROM users` fails with
`ERROR 1451` for anyone who has ordered. `mydb::deleteCustomer()` therefore
deletes the orders first and then the account, both inside one transaction.
Reviews, cart rows and order items are removed by the cascades already in the
schema.

An admin may also open any customer's order confirmation; a customer may open
only their own.

### Still to come

- **Task 1's real login** &mdash; a registration form and password check replace
  `view/login.php`. It only has to set the same three session keys.
- **Task 3's cart and product browsing** &mdash; replaces the product listing and the
  cart table. The payment box and the review section lift out as whole blocks.
- **Git** &mdash; feature branch, at least three commits, pull request into `main`.

---

## Security

| Risk | What the project does |
|------|----------------------|
| SQL injection | Every query taking a user value uses `?` and `bind_param`. 34 queries, only the two `LIMIT` ones interpolate, and both cast to `(int)` first. |
| XSS | Everything PHP prints goes through `e()` (`htmlspecialchars`); jQuery builds review text with `.text()`, never `.html()`. |
| CSRF | A token is created once per session, printed into all nine POST forms with `csrf_field()`, and sent with every AJAX call from a `<meta name="csrf-token">` tag. Each controller and both endpoints reject a POST whose token does not match, using `hash_equals`. |
| Faking a request | Every rule checked in JavaScript is checked again in PHP. |
| Acting as someone else | Ownership is checked in PHP and repeated in the SQL `WHERE`, e.g. `DELETE FROM reviews WHERE id = ? AND user_id = ?`. |

**Passwords are Task 1's.** The seed data stores real bcrypt hashes made with
`password_hash()`, but the sign-in page here does not ask for a password &mdash;
registration and login are Task 1's requirement, and `password_verify()` belongs
in their login controller. This project only reads the session that a login
creates.

---

## Notes on the shared schema

- `reviews` has no `reviewer_name` column, so the reviewer's name is read from
  `users` with a JOIN. One source of truth for the name.
- `orders.payment_method` is `enum('cash','card','bkash')` — not the
  `cash_on_delivery` / `online_wallet` wording used in the project brief.
- `carts.added_at` is an `INT`, so it stores a UNIX timestamp, not a datetime.
- `orders.total_amount` is `decimal(10,0)`, so order totals are stored without
  paisa. `order_items.unit_price` keeps two decimal places.
- `orders` has no `ON DELETE CASCADE` on `user_id`. Deleting a customer must
  delete their orders first, otherwise MySQL blocks the delete.

## Decisions worth knowing

- **Payment method labels.** The brief says "cash on delivery / online wallet",
  the column says `enum('cash','card','bkash')`. The column wins; the three
  options are shown as *Cash on Delivery*, *Credit / Debit Card* and
  *bKash (online wallet)*.
- **Checkout runs in one transaction.** The order row, every order item and the
  emptying of the cart either all succeed or all roll back, so the database can
  never hold an order with no items or a cart cleared without an order.
- **Stock is not reduced when an order is placed.** Checkout refuses a quantity
  larger than the current stock, but changing `products.stock` belongs to
  Task 2, so this task does not write to it.
- **Deleting a customer deletes their orders first.** A plain
  `DELETE FROM users WHERE id = ?` fails with
  `ERROR 1451 ... orders_ibfk_1` whenever the customer has ordered anything,
  because `orders.user_id` has no `ON DELETE CASCADE`. The delete therefore
  runs as orders first, then the account, inside one transaction. Reviews,
  cart rows and order items are removed by the cascades already in the schema.
- **Admins cannot be deleted** from the customer page: the listing filters on
  `role = 'customer'`, the model refuses any non-customer, and `role` is
  repeated in the `DELETE` WHERE clause as a third guard.
>>>>>>> b0e2fc7 (Upload Project)

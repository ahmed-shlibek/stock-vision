# StockVision — Project Guide

A beginner-friendly guide to understanding this codebase. Read it top to bottom and you'll know **what the app does**, **how it's built**, and **where to find things**.

---

## 1. What is StockVision?

StockVision is a **web-based inventory (stock) management system**. A small business uses it to keep track of:

- **Products** they sell or store (with SKU, price, quantity, image, etc.)
- **Categories** that group products
- **Suppliers** that provide products
- **Stock movements** — every time stock comes *in* or goes *out*
- **Alerts** — products running low on stock
- **Users** — the people who log in (all have full access)

It's a classic CRUD app (Create, Read, Update, Delete) plus some inventory-specific logic.

---

## 2. The Tech Stack (what it's made of)

| Layer | Technology | Notes |
|-------|-----------|-------|
| Language | **PHP** (plain, no framework) | Hand-written MVC, no Laravel/Symfony |
| Database | **MySQL** (via PDO) | Tables defined in `database/schema.sql` |
| Frontend | **HTML + CSS + vanilla JavaScript** | No React/Vue. CSS split into small files |
| Server | Designed for **Laragon** (local Windows dev) | Apache + MySQL + PHP bundle |

> **Important:** There is *no framework here*. Everything you see — the routing, the database connection, the templating — was written by hand. That's actually great for learning, because nothing is hidden. You can read every line that runs.

---

## 3. The Big Idea: MVC Architecture

The project follows the **MVC pattern** (Model–View–Controller). This is the single most important concept to understand:

```
         Browser (user clicks a link / submits a form)
                          │
                          ▼
        ┌─────────────────────────────────────┐
        │  public/index.php  (Front Controller)│  ← every request enters HERE
        │  Looks at the URL, finds the route    │
        └─────────────────────────────────────┘
                          │
                          ▼
        ┌─────────────────────────────────────┐
        │  CONTROLLER  (app/controllers/)      │  ← the "traffic cop"
        │  Reads input, decides what to do      │
        └─────────────────────────────────────┘
                 │                        │
                 ▼                        ▼
    ┌──────────────────────┐   ┌──────────────────────┐
    │  MODEL (app/models/) │   │   VIEW (views/)       │
    │  Talks to the DB      │   │   Builds the HTML page │
    └──────────────────────┘   └──────────────────────┘
                 │                        │
                 ▼                        ▼
             MySQL DB              HTML sent to browser
```

- **Model** = data + database logic. "How do I fetch/save products?"
- **View** = the HTML the user sees. "What does the page look like?"
- **Controller** = the glue. "User wants X — get the data from the Model, pass it to the View."

The Controller never writes SQL directly (mostly), and the View never talks to the database. Each layer has one job.

---

## 4. Directory Map

```
StockVision/
│
├── public/                ← The ONLY folder the web server exposes
│   ├── index.php          ← Front controller: ALL requests start here
│   ├── .htaccess          ← Apache rule: send every URL to index.php
│   ├── css/               ← Stylesheets (split by purpose)
│   ├── js/                ← Browser JavaScript
│   └── uploads/           ← User-uploaded product images
│
├── app/                   ← The application brain (NOT web-accessible)
│   ├── config/
│   │   ├── app.php        ← Constants: app name, roles, limits
│   │   └── database.php   ← getDB() — the MySQL connection
│   ├── controllers/       ← One controller per feature
│   ├── models/            ← One model per database table
│   ├── helpers/           ← Reusable functions (auth, validation, etc.)
│   └── routes.php         ← The URL → Controller map (table of contents)
│
├── views/                 ← HTML templates (the "V" in MVC)
│   ├── layouts/           ← Page shells (app.php, auth.php)
│   ├── partials/          ← Reusable pieces (sidebar, topbar, toast)
│   ├── products/          ← Product pages (index, create, edit, show)
│   ├── categories/, suppliers/, stock/, users/, auth/, dashboard/ ...
│   └── errors/404.php
│
├── database/
│   ├── schema.sql         ← Creates all the tables (run this first)
│   └── seed.sql           ← Sample data to play with
│
├── .env                   ← SECRET settings (DB password) — not in git
└── .env.example           ← Template showing what .env should contain
```

### Why `public/` is special
Only `public/` is meant to be reachable from a browser. Everything in `app/` (including your database password logic) lives *outside* the web root so visitors can never download your source code or config. This is a standard security practice.

---

## 5. The Request Lifecycle — follow one click

This is the most valuable thing to understand. Let's trace what happens when a user visits **`/products`**:

1. **Apache** receives the request. The `.htaccess` file rewrites it to `public/index.php`. *(Every URL goes through index.php — this is the "front controller" pattern.)*

2. **`public/index.php`** runs and does setup in order:
   - Starts the session (for login state)
   - Loads `.env` (secrets) and config constants
   - Loads helper functions (auth, validation, response, format)
   - Reads the URL path (`/products`)

3. **Routing** ([app/routes.php](app/routes.php)): index.php loops through the route list looking for a match:
   ```php
   ['GET',  '/products', 'ProductController', 'index'],
   ```
   This says: *a GET request to `/products` should call `ProductController->index()`.*
   Routes with `{id}` (like `/products/{id}/edit`) are turned into a regex so the number in the URL becomes a function argument.

4. **Auth check**: index.php checks if the route is public (only login pages are). For everything else, if you're not logged in, it redirects you to `/login`.

5. **Dispatch**: index.php loads the controller file, loads all models, creates a `new ProductController()`, and calls the `index()` method.

6. **Controller** ([ProductController::index](app/controllers/ProductController.php:25)): reads `?page` and `?search` from the URL, asks the **Product model** for the data, then calls `render()`.

7. **Model** ([Product::getAll](app/models/Product.php:19)): runs the SQL query (with pagination + search), returns rows as a PHP array.

8. **View**: `render()` `extract()`s the data into variables and `require`s the layout `views/layouts/app.php`, which pulls in the sidebar, topbar, and finally `views/products/index.php` — the actual product table.

9. The finished HTML is sent back to the browser. Done.

> **Tip for studying:** Open `public/index.php` and read it top to bottom — it's only ~130 lines and it's the spine of the whole app. Everything else hangs off it.

---

## 6. The Database (the 5 core tables)

Defined in [database/schema.sql](database/schema.sql). The relationships:

```
  users ──────┐
              │ (records who did it)
              ▼
        stock_movements ──────► products ◄────── categories
              ▲                    │   (a product has one category)
              │                    │
        (in / out log)             └────────────► suppliers
                                       (a product has one supplier)
```

| Table | What it holds | Key columns |
|-------|--------------|-------------|
| `users` | People who log in | `email`, `password` (hashed), `role` |
| `categories` | Product groupings | `name`, `color` |
| `suppliers` | Who provides products | `name`, `phone`, `email` |
| `products` | The inventory items | `sku` (unique), `quantity`, `unit_price`, `min_stock_level` |
| `stock_movements` | History of every in/out | `type` ('in'/'out'), `quantity_before`, `quantity_after` |

**Two concepts worth noting:**

- **Soft deletes**: Most tables have a `deleted_at` column. When you "delete" a product, the app doesn't actually remove the row — it just sets `deleted_at` to the current time (see [Product::delete](app/models/Product.php:188)). Every query then filters `WHERE deleted_at IS NULL`. This means deletions are reversible and history is preserved.

- **Stock movements are an audit log**: You never edit a product's `quantity` directly through the edit form. Instead, you record a *movement* (stock in or stock out), and the movement logic updates the quantity. This keeps a complete, trustworthy history of how stock changed and who changed it.

---

## 7. Key Concepts & Where to See Them

### Authentication & Sessions — [app/helpers/auth.php](app/helpers/auth.php)
- `isLoggedIn()`, `currentUserId()`, `currentUserName()`, `currentUserEmail()` — check who's logged in
- Passwords are stored **hashed** (`password_hash` / `password_verify` — never plain text)
- Login flow: [AuthController::login](app/controllers/AuthController.php:35)

### Access control
- There are no roles — every logged-in user has full access to all features, including user management.
- Pages are guarded only by `requireLogin()` in controllers.
- This is a learning/internal project — CSRF protection, login rate-limiting, and session hardening have been intentionally removed. Do not deploy as-is to a hostile environment.

### Database safety — [app/config/database.php](app/config/database.php)
- One shared PDO connection via `getDB()` (the "singleton" pattern)
- **Prepared statements everywhere** (the `?` and `:name` placeholders) — this prevents SQL injection. Notice the models never glue user input directly into SQL strings.

### Transactions — [StockMovement::record](app/models/StockMovement.php:97)
When recording stock in/out, three things must happen together: read the current quantity (locking the row with `FOR UPDATE`), update the product, and insert the movement log. These are wrapped in a **database transaction** so they either *all* succeed or *all* roll back — you can never end up with a movement logged but the quantity wrong. It also rejects taking out more stock than exists.

### Flash messages & form repopulation — [app/helpers/response.php](app/helpers/response.php)
- `setFlash('success', '...')` → a one-time message shown on the next page (the green/red toast)
- `setOld()` / `old()` → re-fill a form with what the user typed when validation fails
- `setErrors()` / `getErrors()` → carry validation errors across a redirect

### Validation — [app/helpers/validation.php](app/helpers/validation.php)
Small reusable functions (`validateRequired`, `validateEmail`, …) that return an error string or `null`. Controllers collect these into an `$errors` array.

---

## 8. The View Layer (how pages are built)

There's no template engine — just PHP files that echo HTML. The trick is **layouts**:

- [views/layouts/app.php](views/layouts/app.php) is the shell for logged-in pages: it loads CSS, draws the sidebar and topbar, then does `require $content;` to drop in the specific page.
- `views/layouts/auth.php` is the simpler shell for the login screen.
- A controller sets `$content` to a page file (e.g. `views/products/index.php`) and requires the layout. The layout fills in the middle.

CSS is intentionally split into small files by purpose (`variables.css`, `base.css`, `components.css`, `layout.css`, `forms.css`, `dashboard.css`) and all loaded in the layout's `<head>`.

JavaScript (`public/js/`) is vanilla and progressive — `app.js` and `utils.js` load everywhere; page-specific scripts (`products.js`, `users.js`) handle things like the product search box and confirm dialogs.

---

## 9. Feature → File cheat sheet

| Feature | Controller | Model | Main views |
|---------|-----------|-------|------------|
| Login / profile | `AuthController` | `User` | `auth/` |
| Dashboard | `DashboardController` | (several) | `dashboard/index.php` |
| Products | `ProductController` | `Product` | `products/` |
| Categories | `CategoryController` | `Category` | `categories/` |
| Suppliers | `SupplierController` | `Supplier` | `suppliers/` |
| Stock in/out & history | `StockController` | `StockMovement` | `stock/` |
| Low-stock alerts | `AlertController` | `Product` | `alerts/index.php` |
| User management (admin) | `UserController` | `User` | `users/` |

> Note: a few controllers/views (`AnalyticsController`, `ReportController`, `analytics/`) and the `logger.php` helper appear in git as removed/in-progress — don't worry if they seem missing or half-wired. Focus on the table above first.

---

## 10. How to run it locally (quick start)

1. Install **Laragon** (bundles Apache + MySQL + PHP).
2. Create the database and tables:
   - Run `database/schema.sql` in MySQL (creates the `stockvision` DB and tables).
   - Run `database/seed.sql` to load sample products/users.
3. Copy `.env.example` to `.env` and fill in your DB credentials (default Laragon: user `root`, empty password).
4. Point Laragon at the **`public/`** folder (that's the web root) and open the site in your browser.
5. Log in with a seeded account (check `database/seed.sql` for the email and password).

---

## 11. A suggested reading order for studying

To learn the codebase efficiently, read files in this order:

1. **`public/index.php`** — the entry point and request flow *(start here!)*
2. **`app/routes.php`** — the map of every URL
3. **`app/config/app.php`** and **`database.php`** — constants and the DB connection
4. **`app/helpers/auth.php`** + **`response.php`** — the shared toolbox controllers rely on
5. **`ProductController.php`** + **`Product.php`** — the most complete feature; learn the Controller↔Model↔View dance here
6. **`StockMovement.php`** (the `record()` method) — the most interesting business logic (transactions)
7. **`views/layouts/app.php`** then **`views/products/index.php`** — how a page is assembled

Once Products clicks for you, every other feature (categories, suppliers, users) follows the exact same pattern — read one, you understand them all.

---

## 12. Glossary (terms you'll keep seeing)

- **Front controller** — one PHP file (`index.php`) that all requests pass through.
- **Route** — a rule mapping a URL + HTTP method to a controller action.
- **CRUD** — Create, Read, Update, Delete (the four basic data operations).
- **PDO** — PHP Data Objects, the standard safe way to talk to a database.
- **Prepared statement** — a SQL query with placeholders; prevents SQL injection.
- **Soft delete** — marking a row as deleted (`deleted_at`) instead of removing it.
- **Session** — server-side memory that remembers you're logged in between pages.
- **Transaction** — a group of DB changes that all succeed or all fail together.
- **Flash message** — a one-time notification shown after a redirect.
```

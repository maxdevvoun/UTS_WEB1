# Tripora v1 — Travel & Tourism Website

**Discover More. Travel Beyond.**

Tripora is a small travel-agency website built for the Pemrograman Web 1 UTS project. It contains a public company-profile/catalog website, account registration and login, role-based dashboards, and a PHP Native REST API connected to MySQL.

## Technology

- HTML5, CSS3, Bootstrap 5.3, and Vanilla JavaScript
- PHP Native (PDO)
- MySQL / MariaDB (XAMPP)
- Postman collection for API testing

## Main features

### Public website
- Home page with company information, destination cards, package catalog, category filters, and search.
- Package detail page loaded dynamically by package ID.
- Registration and login forms with JavaScript validation.

### Accounts and dashboard
- Registration creates an account with the `user` role.
- Login uses PHP sessions and `password_verify()`.
- Passwords are stored with `password_hash()`.
- Admin dashboard provides CRUD for packages, categories, and destinations.
- User dashboard displays account details and active package recommendations.
- API write operations require an authenticated admin session and a CSRF token.

### Database-backed integration
The public catalogue and detail page do **not** use hardcoded package records. They call the PHP API, which reads the MySQL tables. Admin CRUD forms call the same API, which writes changes to the same database. Refreshing the public website displays the updated data.

## Requirements

- XAMPP with Apache, MySQL, and PHP 8.1+.
- PHP extensions enabled: `pdo_mysql` and `session` (XAMPP normally includes these; `mbstring` is optional).
- A modern browser and an internet connection for Bootstrap, Bootstrap Icons, Google Fonts, and the remote Unsplash demo photos.

## Installation on XAMPP

1. Extract or copy this project folder into `C:\xampp\htdocs\`.
2. Rename the folder to **Tripora** (recommended), so the folder path is `C:\xampp\htdocs\Tripora`.
3. Open XAMPP Control Panel and start **Apache** and **MySQL**.
4. Open `http://localhost/phpmyadmin`.
5. Select **Import**, choose `Tripora/database/tripora_db.sql`, then click **Import/Go**.
6. Open `config/database.php` and check the MySQL host, database, username, and password. The defaults are for a standard XAMPP installation: `127.0.0.1`, `tripora_db`, `root`, and an empty password.
7. Open `http://localhost/Tripora/`.

> **Important:** The SQL file recreates the Tripora tables (it drops the existing Tripora tables before creating them). Importing it again will erase data currently stored in those tables. Back up the database before re-importing.

## Demo accounts

**Administrator**

- Email: `admin@tripora.local`
- Password: `TriporaAdmin!2026`

You can also register a normal user through the website. Public registration always creates the `user` role; it cannot create an administrator.

> The administrator credentials are for local coursework demonstration only. Do not deploy this demo account or these credentials to a public server.

## Project structure

```text
Tripora/
├── index.php                  # Public company profile and package catalogue
├── detail.php                 # Dynamic package detail page (?id=)
├── login.php                  # Login page
├── register.php               # Registration page
├── dashboard.php              # Admin/user dashboard (session protected)
├── logout.php                 # Logout fallback
├── api/
│   └── index.php               # REST API router
├── config/
│   ├── app.php                 # Session, base URL, common helpers
│   └── database.php            # PDO MySQL connection
├── includes/
│   ├── header.php
│   └── footer.php
├── assets/
│   ├── css/style.css
│   ├── js/                     # Common, home, detail, auth, dashboard scripts
│   └── images/                 # Local logo and image fallback
├── database/tripora_db.sql
├── docs/
│   ├── API-DOCUMENTATION.md
│   ├── SCREENSHOT-GUIDE.md
│   └── Tripora-Postman-Collection.json
└── README.md
```

## REST API base URL

```text
http://localhost/Tripora/api/index.php
```

The detailed endpoint list, request examples, and authentication notes are in [`docs/API-DOCUMENTATION.md`](docs/API-DOCUMENTATION.md).

## Postman testing

1. Open Postman and import `docs/Tripora-Postman-Collection.json`.
2. Change the collection variable `base_url` if your folder URL is different.
3. Run **Authentication → Get CSRF Token** first.
4. Run **Authentication → Admin Login**. Postman should retain the PHP session cookie and the collection script updates the CSRF token after login.
5. Test public GET endpoints and then the admin CRUD requests.
6. Capture screenshots of successful GET, POST, PUT, and DELETE tests for the UTS README/report.

## Image handling

`image_url` fields accept either:

- An HTTP/HTTPS image URL, or
- A relative local path beginning with `assets/`, for example `assets/images/packages/bali.jpg`.

The sample database uses remote Unsplash demo photos. A local SVG fallback is included if an image cannot load. To use local images, put the image file under `assets/images/` and save the corresponding relative path in the record.

## Footer requirement

All pages include the required footer text:

```text
@Copyright by NPM_NAMA MAHASISWA_KELAS
```

Replace the placeholder in `includes/footer.php` with the required student identity and class before submission.

## Scope notes

- This version is an academic demo. It does not process bookings, payments, or real reservations.
- The dashboard is designed for content management, not commercial travel operations.
- The external assets/CDNs require an internet connection. The PHP and MySQL application itself runs locally in XAMPP.
- This project has been syntax-checked. Database execution and browser integration must be verified in the user's XAMPP environment because MySQL service/configuration is machine-specific.

## Screenshots for submission

Use `docs/SCREENSHOT-GUIDE.md` to capture the website pages, dashboard CRUD, and Postman test results. Place the evidence under a `screenshots/` folder before pushing to GitHub.

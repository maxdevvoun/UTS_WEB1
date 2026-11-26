# Screenshot Guide for UTS Evidence

Capture the real running application after importing the SQL database and starting Apache/MySQL. Do not use mock screenshots in place of actual test evidence.

## Project 1 — Website

1. `01-homepage.png` — home page showing company information, destinations, and travel packages.
2. `02-package-detail.png` — open one package detail from the home page.
3. `03-login.png` — login page and input validation.
4. `04-register.png` — registration page.
5. `05-admin-dashboard.png` — administrator overview.
6. `06-admin-package-crud.png` — package table with add/edit/delete controls.
7. `07-user-dashboard.png` — regular user dashboard after logging in with a registered user account.

## Project 2 — CRUD API

Use the imported Postman collection and capture:

1. `08-api-get-packages.png` — GET `/packages` returns package data from MySQL.
2. `09-api-post-package.png` — POST `/packages` creates a row.
3. `10-api-put-package.png` — PUT `/packages/{id}` changes the row.
4. `11-api-delete-package.png` — DELETE `/packages/{id}` removes the row.
5. Optional: GET `/categories` and GET `/destinations`.

Save screenshots under `screenshots/website/` and `screenshots/postman/`. The README can then embed them using relative Markdown paths.

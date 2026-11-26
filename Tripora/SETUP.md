# Quick Setup — Tripora v1 (XAMPP)

1. Extract the ZIP so the project directory is `C:\xampp\htdocs\Tripora`.
2. Open XAMPP Control Panel and start **Apache** and **MySQL**.
3. Open `http://localhost/phpmyadmin`.
4. Choose **Import** and select `C:\xampp\htdocs\Tripora\database\tripora_db.sql`.
5. Confirm the import completed and the `tripora_db` database contains `users`, `categories`, `destinations`, and `packages`.
6. Check `config/database.php`. Standard XAMPP defaults are:
   - Host: `127.0.0.1`
   - Database: `tripora_db`
   - Username: `root`
   - Password: empty
7. Open `http://localhost/Tripora/`.

## Demo administrator

- Email: `admin@tripora.local`
- Password: `TriporaAdmin!2026`

Register another account from the website to test the regular-user dashboard.

## Important

- Importing the SQL file again drops and recreates the four Tripora tables. Existing data will be erased.
- Make sure PHP extensions `pdo_mysql` and `session` are enabled in XAMPP.
- If the project folder uses another name, use that folder name in the browser URL and in the Postman collection variable `base_url`.
- Replace the footer placeholder `NPM_NAMA MAHASISWA_KELAS` in `includes/footer.php` before submission.

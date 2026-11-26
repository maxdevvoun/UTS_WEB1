# PROJECT PEMROGRAMAN WEB — TRIPORA

Repository ini berisi dua proyek pengembangan web, yaitu pembuatan website Travel & Tourism dan implementasi REST API menggunakan PHP Native dan MySQL.

---

## PROJECT 1 — WEBSITE DEVELOPMENT

### Deskripsi
Tripora merupakan website Travel & Tourism yang menyediakan informasi paket wisata dan destinasi. Website ini dilengkapi dengan fitur registrasi, login, halaman detail paket wisata, serta dashboard pengguna.

**Teknologi:** HTML, CSS, Bootstrap 5.3, JavaScript, PHP Native, dan MySQL.

### Dokumentasi Website

**1. Homepage**

Tampilan halaman utama website Tripora.

![Homepage](Tripora/screenshots/website/HOMEPAGE.jpeg)

**2. Detail Paket Wisata**

Halaman informasi detail paket wisata yang dipilih.

![Detail Package](Tripora/screenshots/website/02-detail-package.png)

**3. Login**

Halaman autentikasi pengguna.

![Login](Tripora/screenshots/website/03-login.png)

**4. Register**

Halaman pendaftaran akun pengguna baru.

![Register](Tripora/screenshots/website/04-register.png)

**5. Dashboard**

Halaman utama setelah pengguna berhasil login.

![Dashboard](Tripora/screenshots/website/05-dashboard.png)

---

## PROJECT 2 — REST API CRUD

### Deskripsi
Project ini mengimplementasikan REST API untuk mengelola data paket wisata, kategori, dan destinasi pada website Tripora. API menggunakan metode CRUD (Create, Read, Update, Delete) dan diuji menggunakan Postman.

**Teknologi:** PHP Native, MySQL, dan Postman.

### Dokumentasi Pengujian API

**1. Authentication**

Pengujian pengambilan CSRF Token dan login administrator.

![CSRF Token](Tripora/screenshots/postman/01-csrf-token.png)

![Admin Login](Tripora/screenshots/postman/02-admin-login.png)

**2. CRUD Packages**

Pengujian pengambilan, penambahan, perubahan, dan penghapusan data paket wisata.

| Pengujian | Dokumentasi |
|---|---|
| GET Packages | ![GET](Tripora/screenshots/postman/03-get-packages.png) |
| POST Create Package | ![POST](Tripora/screenshots/postman/04-create-package.png) |
| PUT Update Package | ![PUT](Tripora/screenshots/postman/05-update-package.png) |
| DELETE Package | ![DELETE](Tripora/screenshots/postman/06-delete-package.png) |
| GET After Delete | ![GET After Delete](Tripora/screenshots/postman/07-get-after-delete.png) |

**3. CRUD Categories**

Pengujian pengambilan dan pengelolaan data kategori wisata.

![GET Categories](Tripora/screenshots/postman/08-get-categories.png)

*Dokumentasi POST, PUT, dan DELETE Categories dapat ditambahkan setelah pengujian selesai.*

**4. CRUD Destinations**

Dokumentasi pengujian pengelolaan data destinasi wisata.

*Screenshot dapat ditambahkan setelah pengujian selesai.*

---

## IDENTITAS MAHASISWA

- **Nama:** [Nama Mahasiswa]
- **NPM:** [NPM]
- **Kelas:** [Kelas]
- **Program Studi:** Teknik Informatika
- **Universitas:** Universitas Teknologi Bandung

---

*Repository ini dibuat untuk memenuhi tugas mata kuliah Pemrograman Web.*

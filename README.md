# Sistem Absensi Klinik

A simple attendance management system built with Laravel 13, designed for small clinic operations. Employees can clock in and clock out daily, while administrators monitor attendance records per date.

![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-%5E8.2-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind-3-38B2AC?logo=tailwind-css&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-green)

---

## Features

- **Authentication** &mdash; register, login, password reset, email verification (Laravel Breeze)
- **Role-based access** &mdash; `admin` and `karyawan` (employee)
- **Clock in / Clock out** &mdash; one per day per employee
- **Late detection** &mdash; automatically flags late arrivals and tracks minutes late
- **Work duration calculation** &mdash; computed from clock-in to clock-out times
- **Per-employee shift time** &mdash; each user can have a custom `jam_masuk`; falls back to a global default
- **Admin attendance monitor** &mdash; browse attendance records filtered by date
- **Toast notifications** &mdash; powered by SweetAlert2, wired to Laravel flash messages
- **Indonesian locale** &mdash; `id` locale and `Asia/Jakarta` timezone by default
- **Profile management** &mdash; update profile, change password, delete account

---

## Tech Stack

| Layer       | Tools                                                    |
| ----------- | -------------------------------------------------------- |
| Backend     | PHP 8.3+, Laravel 13, Eloquent ORM, Vite                |
| Database    | MySQL 5.7+ / MariaDB                                     |
| Frontend    | Blade templates, Tailwind CSS 3, Alpine.js              |
| Notifications | SweetAlert 2                                           |
| Build       | Vite, npm                                                |

---

## Requirements

- PHP **8.3** or higher with extensions: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`
- Composer 2.x
- Node.js 18+ & npm
- MySQL 5.7+ or MariaDB
- (Optional) Laragon / Valet / Docker for local development

---

## Installation

```bash
# 1. Clone the repository
git clone https://github.com/gungrahma/sistem-absensi-simple.git
cd sistem-absensi-simple

# 2. Install PHP dependencies
composer install

# 3. Install JavaScript dependencies
npm install

# 4. Copy the environment file and generate an app key
cp .env.example .env
php artisan key:generate

# 5. Configure your database credentials in .env (see below)

# 6. Run migrations and seed default accounts
php artisan migrate:fresh --seed

# 7. Build frontend assets
npm run build

# 8. Serve the application
php artisan serve
# or use Laragon / Valet for a virtual host
```

---

## Environment Configuration

Edit `.env` with your database credentials:

```env
APP_NAME="Sistem Absensi Klinik"
APP_ENV=local
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://sistem-absensi-sederhana.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_absensi_klinik
DB_USERNAME=root
DB_PASSWORD=

# Default shift start time (used when a user has no custom jam_masuk)
ABSENSI_JAM_MASUK_DEFAULT=08:00:00
```

Create the `sistem_absensi_klinik` database before running migrations.

---

## Default Accounts

The seeder creates one admin and three employees &mdash; all use the password `password`:

| Role     | Email                  | Name            | Jam Masuk |
| -------- | ---------------------- | --------------- | --------- |
| Admin    | `admin@klinik.test`    | Admin Klinik    | 08:00     |
| Karyawan | `budi@klinik.test`     | Budi Santoso    | 08:00     |
| Karyawan | `sari@klinik.test`     | Sari Wulandari  | 08:30     |
| Karyawan | `rina@klinik.test`     | Rina Astuti     | 09:00     |

> For production, change these passwords immediately and remove the seeder.

---

## Usage

### As Karyawan (Employee)

1. Login with your employee account.
2. From the dashboard, click **Clock In Sekarang** to record your arrival.
   - If you clock in after your assigned `jam_masuk`, the system marks your status as **telat** and logs the minutes late.
3. When you finish work, click **Clock Out Sekarang**.
4. The dashboard shows the day's status, including total work duration.
5. You can only clock in and out **once per day**.

### As Admin

1. Login with the admin account.
2. Navigate to **Monitor Absensi** (`/admin/attendance`).
3. Pick a date to see who has clocked in/out and who is late.
4. Click a specific date (`/admin/attendance/{date}`) to drill into a particular day.

---

## Project Structure

```
.
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/AttendanceMonitorController.php   # admin monitoring
│   │   ├── AttendanceController.php                # clock in/out logic
│   │   ├── DashboardController.php
│   │   └── Auth/                                    # Breeze auth controllers
│   ├── Http/Middleware/RoleMiddleware.php          # role-based access
│   ├── Models/
│   │   ├── User.php                                 # + todayAttendance(), isAdmin()
│   │   └── Attendance.php                           # + durasi_kerja accessor
│   └── View/Components/                             # anonymous Blade components
├── config/
│   ├── absensi.php                                  # jam_masuk_default
│   └── ...
├── database/
│   ├── migrations/
│   │   └── 2026_06_21_052154_create_attendances_table.php
│   └── seeders/UserSeeder.php                       # default admin & employees
├── resources/
│   ├── views/
│   │   ├── components/sweetalert.blade.php          # toast/alert bridge
│   │   ├── dashboard.blade.php
│   │   └── admin/attendance.blade.php
│   ├── css/app.css
│   └── js/app.js                                    # imports SweetAlert2
└── routes/web.php
```

---

## Key Domain Rules

- One attendance row per `(user_id, tanggal)` &mdash; enforced by a unique index and `updateOrCreate`.
- `jam_masuk` on `users` overrides the global default from `config/absensi.php`.
- Late status (`status_masuk = 'telat'`) is set when `now() > jam_masuk`.
- `telat_menit` is the integer minute difference between the actual clock-in and the assigned start time.
- `durasi_kerja` is a computed accessor on the `Attendance` model: format `Xj Ym`.

---

## Development

Run Vite in watch mode while developing:

```bash
npm run dev
```

Run tests:

```bash
php artisan test
```

Lint / format code (configure to your taste):

```bash
./vendor/bin/pint   # Laravel Pint, if installed
```

---

## Roadmap

- [ ] Export attendance to Excel / PDF
- [ ] Email notifications for late arrivals
- [ ] Geolocation / IP-based clock-in validation
- [ ] Multi-shift support
- [ ] REST API for mobile client

---

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

---

## License

This project is open-source under the [MIT License](https://opensource.org/licenses/MIT).

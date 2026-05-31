# University Automation — Student Management System

A web-based university management system built with **PHP** and **MySQL**, supporting three user roles: Admin, Teacher, and Student.

## Requirements

- PHP 7.4+
- MySQL 8.0+

## Setup

### 1. Import the Database

```bash
mysql -u root -p < smsdb.sql
```

### 2. Configure Database Credentials

Edit `dbconnection.php` and update the constants:

```php
define('DB_HOST', '127.0.0.1'); // or 'localhost' for Apache/Laragon
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'smsdb');
```

### 3. Run the Server

**Option A — PHP built-in server (Linux/Mac):**

```bash
php -S 127.0.0.1:8000
```

Open `http://127.0.0.1:8000`

**Option B — Apache/LAMP/Laragon:**

Place the project folder in your web root (e.g. `/var/www/html/` or `C:/laragon/www/`) and visit `http://localhost/university-automation/`.

## Default Login

| Role    | Username | Password |
|---------|----------|----------|
| Admin   | admin    | admin    |

Student and teacher accounts are created by the admin.

## Features

### Admin
- Dashboard with attendance overview and payment summaries
- Add / manage students and teachers
- Add / manage courses and sections
- Course scheduling and room management
- Teacher schedule management
- Payment history tracking
- Public notice board management
- Search across records

### Teacher
- Personal dashboard
- View assigned courses and schedules
- Mark and manage student attendance
- Grade students (marks + letter grade)
- Post and manage section notices
- Edit personal profile

### Student
- Personal dashboard
- View enrolled courses and schedules
- View attendance records
- View grades and academic progress
- Course enrollment / checkout (payment flow)
- View section notices
- Edit personal profile

## Project Structure

```
/
├── index.php            # Landing page
├── loginpanel.php       # Role selection (Admin / Teacher / Student)
├── dbconnection.php     # Database configuration
├── smsdb.sql            # Database schema and seed data
├── admin/               # Admin panel
├── teachers/            # Teacher panel
├── students/            # Student panel
├── includes/            # Shared auth, header, footer
└── calender/            # Calendar module
```
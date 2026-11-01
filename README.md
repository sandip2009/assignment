# URL Shortener System (Laravel 12 & MySQL)

Implementing a service which will allow your users to generate short urls. 
will have multiple users Each user will n either be an Admin n or a Member 
Each company will have multiple users 
Each user will n either be an Admin n or a Member
---

## 🛠️ Tech Stack & Requirements

* **PHP:** ^8.2 (Laravel 12)
* **Database:** MySQL / MariaDB
* **Environment:** Local development via XAMPP, Laragon, or Artisan Server

---

## 📋 Project Architecture & Business Logic

### 1. Roles
* **Companies:** The system isolates users into distinct companies. Each company has multiple users.
* **Roles:** 
  * `SuperAdmin` (System-wide elevated access, created via database seeder using raw SQL)
  * `Admin` (Company-level manager)
  * `Member` (Company-level standard user)

### 2. Invitation & Creation Rules
* **SuperAdmin** can invite an `Admin` into a new company.
* **Admin** can invite another `Admin` or `Member` inside their own company.

### 3. URL Shortening & Visibility Rules
* **Creation Constraints:** Configured according to assignment guidelines for Admin, Member, and SuperAdmin roles.
* **Visibility Isolation:**
  * **SuperAdmin:** Can view a list of all short URLs across companies.
  * **Admin:** Can only view the list of short URLs created within their own company.
  * **Member:** Can only view the list of short URLs created by themselves.
* **Redirection:** Short URLs resolve securely and perform an HTTP redirect to the original target URL.

---

## ⚙️ Local Setup Instructions (For Testing)

You can set up this project quickly using either the automated Composer setup script or manual commands.

### Option A: Using the Automated Composer Setup Script (Recommended)
Your project includes an automated setup script in `composer.json`. Run the following command in your terminal:
```bash
composer setup
```
1. Clone the Repository
git clone [https://github.com/YOUR_GITHUB_USERNAME/YOUR_REPOSITORY_NAME.git]
(https://github.com/YOUR_GITHUB_USERNAME/YOUR_REPOSITORY_NAME.git)
cd YOUR_REPOSITORY_NAME

2. Install PHP Dependencies
```bash
composer install
```
3. Configure Environment File
Copy the environment file and configure your local MySQL database credentials:
```bash
cp .env.example .env
```
Open .env and update your database settings:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=url_shortener_db
DB_USERNAME=root
DB_PASSWORD=

4. Generate Application Key (if need)
```bash
php artisan key:generate
```

5. Run Migrations & Seeders
Run migrations and seed the database with your raw SQL SuperAdmin account:
```bash
php artisan migrate --seed
```
if seed not work 
```bash
php artisan db:seed --class=SuperAdminSeeder
```
```bash
php artisan serve
```
use this url 
```bash
http://localhost:8000/login 
```
for super admin
email : superadmin@example.com
password : SuperAdmin@123

## 📧 Invitation Events, Listeners & Queue System

In this application, email invitations are decoupled using **Laravel Events and Listeners** and processed asynchronously through **Queues** for optimal performance:

1. **SuperAdmin Invitation Event:** Triggered when the SuperAdmin sends an invitation to create a new company.
2. **Company Admin Invitation Event:** Triggered when a Company Admin sends an invitation to a new Admin or Member.

### ⚠️ Important Note for Testing (Queue Execution)

Since email dispatching is queued, **emails will not be sent instantly without running a queue worker**. 

When testing invitation flows locally, please ensure you run one of the following commands in a separate terminal window:

#### Option 1: Run Queue Worker (Recommended for testing)
```bash
php artisan queue:work
```

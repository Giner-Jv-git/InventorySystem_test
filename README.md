# Inventory Management System

A modern claymorphic inventory management application built with Laravel 12 with photo uploads, soft deletes/trash management, and PDF export capabilities.

## Requirements

- PHP 8.1+
- Composer
- Node.js & npm (v16+)
- MySQL/MariaDB
- Git

## 📋 Step-by-Step Installation Guide

### Prerequisites Setup

Make sure you have the following installed on your system:
- PHP 8.1 or higher (with `fileinfo`, `pdo_mysql` extensions)
- Composer (latest version)
- Node.js v16+ with npm
- MySQL Server 5.7+ or MariaDB 10.2+
- Git

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/InventorySystem_test.git
cd InventorySystem_test
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

This will install all required PHP packages including:
- Laravel framework
- Laravel Breeze (authentication)
- DomPDF (PDF generation)
- And other dependencies

### Step 3: Create Environment File

```bash
cp .env.example .env
```

If `.env.example` doesn't exist, create `.env` manually with the content from your Git repository.

### Step 4: Generate Application Key

```bash
php artisan key:generate
```

This creates a unique encryption key for your application.

### Step 5: Configure Database Connection

Open `.env` file and update the database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_system
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

**Note:** Create the database manually first:
```bash
# Using MySQL CLI
mysql -u root -p
CREATE DATABASE inventory_system;
EXIT;
```

Or use phpMyAdmin to create the database.

### Step 6: Install Node Dependencies

```bash
npm install
```

This installs frontend dependencies (Tailwind CSS, Vite, etc.).

### Step 7: Run Database Migrations

```bash
php artisan migrate
```

This creates all necessary database tables:
- Users table
- Categories table
- Products table
- Soft delete support (deleted_at columns)

### Step 8: Create Storage Symlink

```bash
php artisan storage:link
```

This creates a symlink from `public/storage` to `storage/app/public` so uploaded photos are accessible.

### Step 9: Build Frontend Assets

```bash
npm run build
```

This compiles Tailwind CSS and other frontend assets.

### Step 10: Start the Application

**Option A: Using Laravel's built-in server**

Terminal 1 - Start Laravel server:
```bash
php artisan serve
```

Terminal 2 - Start Vite dev server (optional, for hot reload):
```bash
npm run dev
```

Visit `http://localhost:8000` in your browser.

**Option B: Using XAMPP/Apache**

1. Place project in `htdocs` folder
2. Configure Apache virtual host or access via `http://localhost/InventorySystem_test/public`
3. Ensure storage symlink exists
4. Run: `php artisan serve` for Artisan development server

### Step 11: Create User Account

1. Click "Register" on the login page
2. Create your account with email and password
3. Log in to access the dashboard

---

## 🎯 Features

### Phase 1: Photo Management
- ✅ Photo upload for categories and products
- ✅ Automatic avatar generation with initials if no photo
- ✅ Photo display in all tables and edit forms
- ✅ Photo deletion when items are deleted

### Phase 2.1: Soft Deletes & Trash Management
- ✅ Soft delete functionality (items move to trash)
- ✅ Trash/Recycle bin for categories and products
- ✅ Restore deleted items
- ✅ Permanent deletion with file cleanup
- ✅ Easy access from sidebar navigation

### Phase 2.2: PDF Export
- ✅ Export categories list to PDF
- ✅ Export products inventory to PDF
- ✅ Professional report formatting
- ✅ Includes summary statistics
- ✅ Automatic filename with timestamp

### Core Features
- Dashboard with inventory overview
- Product management (Create, Read, Update, Delete, Restore, Permanent Delete)
- Category management (Create, Read, Update, Delete, Restore, Permanent Delete)
- Modern claymorphic UI design
- User authentication with Laravel Breeze
- Search and filtering functionality
- Responsive design (mobile-friendly)

## 📁 Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CategoryController.php
│   │   │   └── ProductController.php
│   │   └── Requests/
│   ├── Models/
│   │   ├── Category.php
│   │   ├── Product.php
│   │   └── User.php
│   └── Helpers/
│       └── AvatarHelper.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── resources/
│   ├── views/
│   │   ├── categories/
│   │   ├── products/
│   │   ├── pdfs/
│   │   ├── layouts/
│   │   └── auth/
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php
│   ├── auth.php
│   └── console.php
├── storage/
│   ├── app/public/photos/
│   ├── framework/
│   └── logs/
├── public/
│   ├── storage/ (symlink)
│   └── build/
└── vendor/
```

## 🚀 Common Tasks

### Seed Sample Data

```bash
php artisan db:seed
```

This populates the database with sample categories and products.

### Clear Application Cache

```bash
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

### Generate New API Keys (if needed)

```bash
php artisan key:generate
```

### Reset Database (Warning: Deletes all data)

```bash
php artisan migrate:reset
php artisan migrate
```

### Watch Mode for Frontend Development

```bash
npm run dev
```

---

## 📸 Screenshot Features

- **Categories Page**: View, create, edit, and delete categories with photos
- **Products Page**: Manage inventory with pagination and search
- **Trash Pages**: Recover or permanently delete soft-deleted items
- **PDF Export**: Download professional reports with timestamps
- **Dashboard**: Quick overview of inventory status

## 🔒 Security Notes

- Never commit `.env` file to Git (already in `.gitignore`)
- Keep `APP_KEY` secure (generated automatically)
- Use strong database passwords in production
- Enable HTTPS in production
- Update dependencies regularly: `composer update` and `npm update`

## 🐛 Troubleshooting

### Photos not displaying?
```bash
php artisan storage:link
php artisan view:clear
```

### Database connection error?
- Verify MySQL is running
- Check DB credentials in `.env`
- Ensure database exists: `CREATE DATABASE inventory_system;`

### Node modules issues?
```bash
rm -r node_modules package-lock.json
npm install
npm run build
```

### Port 8000 already in use?
```bash
php artisan serve --port=8001
```

### Permission errors on storage folder?
```bash
chmod -R 775 storage bootstrap/cache
```

## 📚 Documentation Links

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Breeze](https://laravel.com/docs/authentication)
- [Tailwind CSS](https://tailwindcss.com)
- [DomPDF](https://github.com/barryvdh/laravel-dompdf)

## 📝 License

MIT License

## 👨‍💻 Support

For issues or questions, please open an issue on GitHub or check the Laravel documentation.

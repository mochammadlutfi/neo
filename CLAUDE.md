# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Frontend Asset Compilation
- **Development**: `npm run dev` - Start Vite development server with HMR
- **Production Build**: `npm run build` - Build optimized assets for production

### Backend (Laravel)
- **Serve Application**: `php artisan serve` - Start Laravel development server
- **Run Tests**: `php artisan test` or `vendor/bin/phpunit` - Execute PHPUnit tests
- **Database Migration**: `php artisan migrate` - Run database migrations
- **Database Seeding**: `php artisan db:seed` - Seed database with test data
- **Clear Cache**: `php artisan cache:clear` - Clear application cache
- **Generate App Key**: `php artisan key:generate` - Generate encryption key

### Asset Management
- **Install Dependencies**: `composer install` (PHP) and `npm install` (Node.js)

## Code Architecture

### Framework & Stack
- **Backend**: Laravel 9 PHP framework
- **Frontend**: Vite build system with SCSS/JavaScript compilation
- **UI Framework**: Bootstrap 5.2.3 with custom Codebase admin theme
- **Database**: Laravel Eloquent ORM with migrations
- **Authentication**: Dual authentication system (users and admins)

### Key Directory Structure
- `app/Http/Controllers/` - Main application controllers with Admin subdirectory
- `app/Models/` - Eloquent models (User, Order, Project, Journey, Kategori, etc.)
- `app/Services/Midtrans/` - Payment gateway integration services
- `resources/views/` - Blade templates organized by feature (admin, landing, auth)
- `resources/sass/` - SCSS files including Bootstrap and Codebase theme variants
- `routes/web.php` - All web routes with user and admin route groups

### Core Business Logic
- **Training/Course Management**: Orders, packages, and journey tracking system
- **Project Management**: Task tracking with calendar integration
- **Payment Processing**: Midtrans payment gateway integration
- **User Management**: Dual-role system (regular users and admin staff)
- **Content Management**: Categories, packages, and journey steps

### Authentication Architecture
- **User Authentication**: Standard Laravel auth for customers
- **Admin Authentication**: Separate admin guard with middleware protection
- **Route Protection**: Middleware-based access control for user/admin areas

### Database Architecture
- Core entities: Users, Orders, Projects, Tasks, Journeys, Payments
- Relationship-heavy design with proper foreign key constraints
- Uses Laravel migrations for schema management

### Frontend Architecture
- **Theme System**: Multiple Codebase theme variants (corporate, earth, elegance, flat, pulse)
- **Asset Pipeline**: Vite handles SCSS compilation and JavaScript bundling
- **DataTables Integration**: Laravel DataTables package for admin interfaces
- **Form Handling**: Bootstrap-styled forms with Laravel validation

### Payment Integration
- Midtrans payment gateway with configuration in `config/midtrans.php`
- Snap token generation and callback handling services
- Payment status tracking and order management integration

### Development Environment
- Configured for local development with Laragon (SSL certificate paths in vite.config.js)
- Indonesian localization (id_ID) with fallback to English
- UTC timezone with Indonesian locale settings
# 🎓 Aplikasi Pembayaran SPP - Modernisasi Laravel 11

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](LICENSE)

**Status:** ✅ Production Ready (Fase 1-6 Complete)

---

## 📋 Overview

Aplikasi Pembayaran SPP (Sumbangan Pembinaan Pendidikan) yang telah dimodernisasi menggunakan **Laravel 11** dengan arsitektur modern, keamanan tingkat enterprise, dan fitur laporan komprehensif.

Dari aplikasi legacy PHP (2021) hingga sekarang telah ditingkatkan menjadi aplikasi enterprise-grade dengan:
- ✅ Authentication & Authorization multi-role
- ✅ Payment Gateway Integration
- ✅ Professional Invoice PDF Generation
- ✅ Payment Slip Printer (Dual Copy)
- ✅ Excel Data Export
- ✅ Advanced Reporting & Analytics
- ✅ Security Middleware
- ✅ API Endpoints

---

## 🚀 Features

### 🔐 Authentication (Fase 4)
- ✅ Multi-user login (Admin, Guru, Siswa, Orang Tua)
- ✅ Email verification
- ✅ Password reset
- ✅ Session management
- ✅ 2FA ready

### 💳 Payment Integration (Fase 5)
- ✅ Payment gateway support (Bank, E-Wallet, Cash)
- ✅ Payment status tracking (Pending → Verified → Paid)
- ✅ Verification workflow
- ✅ Email notifications
- ✅ Receipt generation
- ✅ API endpoints untuk mobile

### 📊 Reports & Exports (Fase 6)
- ✅ **Professional Invoice PDF**
  - Detailed payment information
  - Student & parent data
  - Verification signatures
  - Professional styling

- ✅ **Payment Slip Printer**
  - Dual copy (Student + School)
  - Thermal printer optimized
  - Amount in text format
  - Easy tear-off design

- ✅ **Excel Export**
  - Full data export (18 columns)
  - Filter by: Status, Month, Class
  - Professional styling
  - Auto-formatting

- ✅ **Reports Dashboard**
  - Real-time statistics
  - 6-month trend chart
  - Recent payments table
  - Quick actions

- ✅ **Payment Report**
  - Advanced filtering
  - Pagination
  - Export functionality
  - Verification tracking

- ✅ **Monthly Statistics**
  - Daily breakdown chart
  - Status analysis
  - Payment method breakdown
  - Top students ranking

- ✅ **Class Report**
  - Per-class analysis
  - Student payment summary
  - Class export functionality

### 🔒 Security
- ✅ Role-based access control (Admin, Guru, Siswa)
- ✅ Authorization middleware
- ✅ CSRF protection
- ✅ SQL injection prevention
- ✅ XSS protection
- ✅ Rate limiting
- ✅ Secure password hashing

---

## 📦 Tech Stack

| Component | Version | Purpose |
|-----------|---------|----------|
| **Framework** | Laravel 11 | Web framework |
| **PHP** | 8.2+ | Server language |
| **Database** | MySQL 8.0 | Data storage |
| **PDF** | DomPDF 2.1 | Invoice/Slip generation |
| **Excel** | Maatwebsite Excel 3.1 | Data export |
| **Authentication** | Laravel Sanctum 4.0 | API & session auth |
| **Frontend** | Blade Templates | View rendering |
| **Charts** | Chart.js | Data visualization |

---

## 🛠️ Installation

### Prerequisites
```bash
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js 18+ (optional, untuk asset compilation)
```

### Setup

```bash
# 1. Clone repository
git clone https://github.com/rimitchistore-tech/New-pembayaran-spp.git
cd New-pembayaran-spp

# 2. Install dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Database setup
php artisan migrate
php artisan db:seed

# 5. Install Excel plugin
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"

# 6. Run application
php artisan serve
```

### Environment Configuration

```env
# .env
APP_NAME="SPP Payment System"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spp_payment
DB_USERNAME=root
DB_PASSWORD=

SCHOOL_NAME="Sekolah ABC"
SCHOOL_ADDRESS="Jl. Pendidikan No. 123"
SCHOOL_PHONE="0812-1234-5678"
SCHOOL_EMAIL="admin@sekolah.com"
```

---

## 📖 Usage

### Default Credentials
```
👤 Admin:
   Email: admin@example.com
   Password: password

👨‍🏫 Guru:
   Email: guru@example.com
   Password: password

👨‍🎓 Siswa:
   Email: siswa@example.com
   Password: password
```

### Common Routes

```
🏠 Dashboard: /dashboard
📊 Reports: /reports/dashboard
📋 Payment Report: /reports/payment
📈 Monthly Stats: /reports/monthly
💳 Invoice PDF: /reports/invoice/{id}/preview
🖨️ Payment Slip: /reports/slip/{id}/print
📥 Export Excel: /reports/payment/export
```

---

## 📁 Project Structure

```
New-pembayaran-spp/
├── app/
│   ├── Models/                  # Eloquent models
│   ├── Http/
│   │   ├── Controllers/         # Application controllers
│   │   ├── Middleware/          # Custom middleware
│   │   └── Requests/            # Form requests
│   └── Services/                # Business logic
│       ├── InvoicePdfService.php
│       ├── PaymentExcelService.php
│       └── PaymentSlipService.php
├── database/
│   ├── migrations/              # Database migrations
│   ├── seeders/                 # Database seeders
│   └── factories/               # Model factories
├── resources/
│   ├── views/
│   │   ├── reports/             # Report templates
│   │   ├── payments/            # Payment views
│   │   └── layouts/             # Layout templates
│   └── assets/                  # CSS, JS, images
├── routes/
│   ├── web.php                  # Web routes
│   ├── api.php                  # API routes
│   └── reports.php              # Report routes
├── config/                      # Configuration files
├── bootstrap/                   # Bootstrap configuration
├── public/                      # Public assets
├── storage/                     # File storage
├── tests/                       # Test files
├── .env.example                 # Environment example
├── composer.json                # PHP dependencies
└── README.md                    # This file
```

---

## 🔄 Database Schema

### Core Tables
- **users** - User accounts (Admin, Guru, Siswa, Orang Tua)
- **classes** - School classes
- **students** - Student data
- **spp** - SPP (tuition) data
- **payments** - Payment transactions
- **payment_methods** - Payment methods
- **payment_verifications** - Verification history

### Relationships
```
Payment → User (many-to-one)
Payment → PaymentMethod (many-to-one)
Payment → User/Verifier (verified_by)
Payment → PaymentVerification (one-to-many)
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test class
php artisan test tests/Feature/ReportControllerTest.php

# Run with coverage
php artisan test --coverage
```

---

## 🚀 Deployment

### Production Checklist
```bash
# 1. Set production environment
APP_ENV=production
APP_DEBUG=false

# 2. Run migrations
php artisan migrate --force

# 3. Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Optimize auto-loader
composer install --optimize-autoloader --no-dev

# 5. Storage permissions
chmod -R 775 storage bootstrap/cache
```

---

## 📊 Phase Completion

| Phase | Description | Status |
|-------|-------------|--------|
| **Fase 1** | Laravel Setup + Database | ✅ Complete |
| **Fase 2** | Blade Templates | ✅ Complete |
| **Fase 3** | Security Middleware | ✅ Complete |
| **Fase 4** | Authentication Pages | ✅ Complete |
| **Fase 5** | Payment Integration | ✅ Complete |
| **Fase 6** | Reports & Exports | ✅ Complete |
| **Fase 7+** | Future enhancements | ⏳ Pending |

---

## 📝 Future Enhancements (Fase 7+)

- [ ] Email notifications for payments
- [ ] SMS reminders
- [ ] Mobile app API
- [ ] Advanced analytics dashboard
- [ ] Payment gateway webhook integration
- [ ] Automated reconciliation
- [ ] Bulk payment import
- [ ] QR code payment links

---

## 🤝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📄 License

MIT License - see LICENSE file for details

---

## 📞 Support

For issues and questions:
- 📧 Email: support@sekolah.com
- 🐛 GitHub Issues: [Issue Tracker](https://github.com/rimitchistore-tech/New-pembayaran-spp/issues)
- 📖 Documentation: [Wiki](https://github.com/rimitchistore-tech/New-pembayaran-spp/wiki)

---

## 🎯 Project Status

**Last Updated:** 2026-07-06  
**Maintainer:** @rimitchistore-tech  
**Version:** 1.0.0  
**Status:** ✅ Production Ready

---

**Developed from 2021 legacy PHP to 2026 modern Laravel 11 enterprise application** 🚀

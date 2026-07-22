# বেতাগী ই-সেবা (Betagi E-Sheba) Platform

> **Digital Governance Platform for Betagi Upazila, Barishal, Bangladesh**

This repository contains the complete source code for the Betagi E-Sheba digital services platform, which enables citizens to access government services, information, and resources digitally.

---

## 📁 Project Structure

```
/workspace/
├── AndroidStudioProjects/     # Citizen-facing Android Mobile App
│   ├── app/                   # Main application code
│   ├── build.gradle.kts       # Build configuration
│   └── README.md              # Detailed app documentation
│
├── betagi_backend/            # Backend API (PHP + MySQL)
│   ├── api/                   # REST API endpoints
│   ├── database/              # Database schema
│   ├── uploads/               # File upload directory
│   └── README.md              # Detailed backend documentation
│
├── Upazila-APp/               # (Reserved for future use)
│
└── README.md                  # This file
```

---

## 🚀 Projects Overview

### 1. Android Mobile App (`AndroidStudioProjects/`)

The citizen-facing Android application that provides access to all Betagi Upazila services.

**Key Features:**
- User registration & authentication (phone + password, Google Sign-In)
- View upazila notices with filtering
- Browse 42+ services (hospitals, clinics, doctors, blood donors, schools, etc.)
- Blood donor search and contact
- Directory listings (clinics, doctors, government officers, businesses)
- Complaint filing system
- Profile management
- Government online services access via WebView

**Tech Stack:**
- Language: Kotlin/Java
- Min SDK: 26 (Android 8.0 Oreo)
- Target SDK: 36
- Backend Integration: REST API

📖 **See [AndroidStudioProjects/README.md](AndroidStudioProjects/README.md) for detailed documentation**

---

### 2. Backend API (`betagi_backend/`)

PHP + MySQL REST API that powers the mobile and web applications.

**Core Services:**
- JWT-based user authentication
- Union information management (7 unions)
- Notice management system
- Blood donor directory
- Clinic/Hospital information
- Doctor directory
- Emergency contacts
- Complaint management system
- Unified person/business/government registries
- Admin dashboard with statistics

**Tech Stack:**
- Backend: PHP 7.4+
- Database: MySQL 5.7+ / MariaDB
- Authentication: JWT
- Server: Apache/Nginx

📖 **See [betagi_backend/README.md](betagi_backend/README.md) for detailed documentation**

---

## 🌟 Platform Features

### Citizen Services
- Access to upazila information and notices
- Healthcare facility finder (clinics, hospitals, doctors)
- Blood donor network
- Emergency contact directory
- Online complaint submission
- Government service links (birth/death certificates, e-passport, etc.)
- Budget transparency information

### Administrative Features
- User management
- Content management (notices, directories)
- Complaint tracking and resolution
- Statistical dashboard
- Role-based access control

---

## 🛠️ Getting Started

### Prerequisites

- **For Mobile App Development:**
  - Android Studio Arctic Fox or later
  - JDK 11 or higher
  - Android SDK 26+

- **For Backend Development:**
  - PHP 7.4 or higher
  - MySQL 5.7 or higher / MariaDB
  - Apache or Nginx web server
  - Composer (optional)

### Quick Start

#### Backend Setup
```bash
cd betagi_backend
cp .env.example .env
# Edit .env with your database credentials
mysql -u username -p nagorik1_betagi < database/schema.sql
```

#### Android App Setup
```bash
cd AndroidStudioProjects
# Open in Android Studio
# Sync Gradle and run
```

📖 **Detailed installation instructions are available in each project's README**

---

## 📋 API Endpoints

The backend provides RESTful APIs for:

| Endpoint | Description |
|----------|-------------|
| `/api/login.php` | User login |
| `/api/register.php` | User registration |
| `/api/notices.php` | Fetch notices |
| `/api/blood_donors.php` | Blood donor directory |
| `/api/clinics.php` | Clinic/Hospital list |
| `/api/doctors.php` | Doctor directory |
| `/api/complaints.php` | Submit/view complaints |
| `/api/profile.php` | User profile management |
| `/api/unions.php` | Union information |

📖 **Full API documentation available in [betagi_backend/README.md](betagi_backend/README.md)**

---

## 🔐 Security

- JWT-based authentication
- Password hashing with bcrypt
- SQL injection prevention
- XSS protection
- CORS configuration
- Environment variable management for sensitive data

---

## 📱 Supported Services

The platform provides access to 42+ services including:

- Healthcare (Hospitals, Clinics, Doctors, Blood Donors)
- Education (Schools, Colleges)
- Government Services (Union offices, Emergency services)
- Business Directory (Shops, Markets)
- Transportation (Bus services)
- Tourism (Tourist spots)
- Online Services (Birth certificate, Death certificate, E-passport)

---

## 👥 Target Users

- **Citizens:** Access government services and information
- **Healthcare Providers:** List clinics and services
- **Business Owners:** Register businesses in directory
- **Government Officials:** Manage services and respond to complaints
- **Administrators:** Full platform management

---

## 📄 License

This project is part of the Betagi Upazila digital governance initiative.

---

## 🤝 Contributing

For contribution guidelines, please refer to the individual project READMEs or contact the development team.

---

## 📞 Support

For technical support or queries:
- Check individual project READMEs for specific issues
- Contact the Betagi Upazila IT department

---

## 🌍 Language

The user interface is primarily in **Bengali (বাংলা)** to serve the local population effectively.

---

**বেতাগী ই-সেবা - আপনার সেবায় নিবেদিত**  
*Betagi E-Sheba - Dedicated to Your Service*

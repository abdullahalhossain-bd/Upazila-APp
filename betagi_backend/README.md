# বেতাগী ই-সেবা (Betagi E-Sheba) - Backend API

A comprehensive backend API system for Betagi Union's digital services platform, built with PHP and MySQL.

## 📋 Overview

Betagi E-Sheba is a digital governance platform that provides various citizen services for Betagi Union. This backend API powers the mobile and web applications, enabling citizens to access government services, information, and resources digitally.

## 🌟 Features

### Core Services
- **User Authentication** - JWT-based secure login/registration system
- **Union Information** - Details about all 7 unions in Betagi
- **Notice Management** - Government notices and announcements
- **Blood Donor Directory** - Connect with blood donors in the area
- **Clinic/Hospital Information** - Healthcare facility details
- **Specialist Doctors** - Doctor directory with specialization info
- **Emergency Numbers** - Quick access to emergency contacts
- **Complaint System** - Submit and track citizen complaints
- **Profile Management** - User profile and password updates

### Unified Services
- Unified Person Database
- Unified Business Registry
- Government Items Registry
- Government Officer Directory

### Admin Features
- Admin dashboard with statistics
- User management (role & status updates)
- Budget category management
- Content management (notices, doctors, clinics, etc.)
- Complaint status management

## 🏗️ Project Structure

```
betagi_backend/
├── api/
│   ├── config/
│   │   └── config.php          # Database & app configuration
│   ├── *.php                   # API endpoints
│   └── .htaccess               # Apache configuration
├── database/
│   └── schema.sql              # MySQL database schema
├── uploads/                    # File upload directory
├── .env.example                # Environment variables template
└── README.md                   # This file
```

## 🚀 Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB
- Apache or Nginx web server
- Composer (optional, for dependencies)

### Step 1: Clone the Repository

```bash
git clone <repository-url>
cd betagi_backend
```

### Step 2: Database Setup

1. Create a new MySQL database:
```sql
CREATE DATABASE nagorik1_betagi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Import the schema:
```bash
mysql -u username -p nagorik1_betagi < database/schema.sql
```

### Step 3: Environment Configuration

1. Copy the example environment file:
```bash
cp .env.example .env
```

2. Edit `.env` with your production values:
```env
DB_HOST=your_db_host
DB_NAME=nagorik1_betagi
DB_USER=your_db_user
DB_PASS=your_secure_password
JWT_SECRET=your_64_char_random_secret_here
```

3. Generate a secure JWT secret:
```bash
openssl rand -hex 32
```

### Step 4: Configure Database Connection

Update `api/config/config.php` if needed (though it reads from `.env`):
- DB_HOST: Your database host
- DB_NAME: Your database name
- DB_USER: Your database username
- DB_PASS: Your database password

### Step 5: Set Permissions

Ensure proper permissions for uploads directory:
```bash
chmod 755 uploads/
chown www-data:www-data uploads/  # For Apache/Nginx
```

### Step 6: Web Server Configuration

#### Apache
The `.htaccess` file in the `api/` directory handles routing and CORS.

#### Nginx
Configure FastCGI parameters to pass environment variables:
```nginx
fastcgi_param DB_HOST your_db_host;
fastcgi_param DB_NAME your_db_name;
fastcgi_param DB_USER your_db_user;
fastcgi_param DB_PASS your_db_password;
fastcgi_param JWT_SECRET your_jwt_secret;
```

## 🔌 API Endpoints

### Authentication
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/register.php` | User registration |
| POST | `/login.php` | User login |
| POST | `/admin_login.php` | Admin login |
| GET | `/profile.php` | Get user profile |
| POST | `/update_profile.php` | Update user profile |
| POST | `/update_password.php` | Change password |

### Notices
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/get_notice.php` | Fetch all notices |
| POST | `/add_notice.php` | Add new notice (Admin) |
| POST | `/update_notice.php` | Update notice (Admin) |
| POST | `/delete_notice.php` | Delete notice (Admin) |

### Blood Donors
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/get_donor.php` | Get blood donors |
| POST | `/add_donor.php` | Add donor |
| POST | `/update_donor.php` | Update donor |
| POST | `/delete_donor.php` | Delete donor |

### Clinics
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/get_clinic.php` | Get clinic information |
| POST | `/add_clinic.php` | Add clinic |
| POST | `/update_clinic.php` | Update clinic |
| POST | `/delete_clinic.php` | Delete clinic |

### Specialist Doctors
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/get_specialist_doctor.php` | Get doctor list |
| POST | `/add_specialist_doctor.php` | Add doctor |
| POST | `/update_specialist_doctor.php` | Update doctor |
| POST | `/delete_specialist_doctor.php` | Delete doctor |

### Emergency Services
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/get_emergency_numbers.php` | Get emergency contacts |
| POST | `/add_emergency_number.php` | Add emergency number |
| POST | `/update_emergency_number.php` | Update emergency number |
| POST | `/delete_emergency_number.php` | Delete emergency number |

### Complaints
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/submit_complaint.php` | Submit complaint |
| GET | `/get_complaints.php` | Get complaints |
| POST | `/update_complaint_status.php` | Update complaint status |

### Unified Services
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/get_unified_person.php` | Get person records |
| POST | `/add_unified_person.php` | Add person |
| GET | `/get_unified_business.php` | Get business records |
| POST | `/add_unified_business.php` | Add business |
| GET | `/get_unified_govt_item.php` | Get govt items |
| GET | `/get_unified_govt_officer.php` | Get govt officers |

### Admin Operations
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/admin_dashboard_stats.php` | Dashboard statistics |
| GET | `/admin_list_users.php` | List all users |
| POST | `/admin_update_user_role.php` | Update user role |
| POST | `/admin_update_user_status.php` | Update user status |
| POST | `/admin_add_budget_category.php` | Add budget category |
| POST | `/admin_update_budget_category.php` | Update budget category |
| POST | `/admin_delete_budget_category.php` | Delete budget category |

## 🔐 Security Features

- **JWT Authentication**: Secure token-based authentication
- **Environment Variables**: Sensitive credentials stored in `.env`
- **CORS Protection**: Configured for specific origins
- **SQL Injection Prevention**: Prepared statements used throughout
- **Password Hashing**: Bcrypt for secure password storage
- **File Upload Security**: Validated file types and paths

## 📊 Database Schema

The database includes the following main tables:
- `unions` - Union council information
- `users` - Citizen/user accounts
- `notices` - Government notices
- `notice_attachments` - Notice files
- `blood_donors` - Blood donor registry
- `clinics` - Healthcare facilities
- `specialist_doctors` - Doctor directory
- `emergency_numbers` - Emergency contacts
- `notifications` - Push notifications
- `complaints` - Citizen complaints
- `budget_categories` - Budget tracking
- Unified service tables (persons, businesses, govt items, officers)

## 🛠️ Development

### Local Development Setup

1. Use PHP's built-in server for testing:
```bash
cd api
php -S localhost:8000
```

2. Or set up a local LAMP/LEMP stack

3. Update `BASE_URL` in `config.php` for local development

### Testing API Endpoints

Example using cURL:
```bash
# Register a new user
curl -X POST http://localhost:8000/register.php \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "phone": "01700000000",
    "password": "securepassword123",
    "address": "Betagi Sadar",
    "union_name": "বেতাগী সদর ইউনিয়ন পরিষদ"
  }'

# Login
curl -X POST http://localhost:8000/login.php \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "01700000000",
    "password": "securepassword123"
  }'
```

## 📝 Environment Variables

| Variable | Description | Required |
|----------|-------------|----------|
| `DB_HOST` | Database host | Yes |
| `DB_NAME` | Database name | Yes |
| `DB_USER` | Database username | Yes |
| `DB_PASS` | Database password | Yes |
| `JWT_SECRET` | JWT signing secret (64+ chars) | Yes |

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is part of the Betagi E-Sheba initiative for digital governance.

## 📞 Support

For support and queries:
- Email: [Contact through official channels]
- Website: https://nagoriksheba.com

## 🙏 Acknowledgments

- Betagi Union Council
- All contributors to the E-Sheba initiative
- The citizens of Betagi Union

---

**বেতাগী ই-সেবা** - Digital Services for Better Governance

# ITForm Enterprise Application - Complete Installation & Setup Guide

## 📋 Overview

**ITForm Enterprise** is a production-ready IT Request Management System designed for Prime Bisco Nigeria Limited. It manages complex approval workflows, multi-app requests, role-based access, and comprehensive audit trails.

**Current Version:** 1.0.0  
**Technology Stack:** PHP 7.1+, SQL Server 2016+, HTML5, CSS3, JavaScript  
**Architecture:** Pure Native PHP with MVC + Service Layer Pattern  

---

## 🚀 Quick Start

### Minimum Requirements

- **Operating System:** Windows 7+ / Linux / macOS
- **Web Server:** Apache 2.4+ (XAMPP 3.2.2+)
- **PHP:** 7.1 or higher (with PDO support)
- **Database:** SQL Server 2016+ OR MySQL 5.7+
- **Disk Space:** 500 MB
- **RAM:** 2 GB minimum
- **Browser:** Chrome 60+, Firefox 55+, Edge 79+

### Installation Steps

#### Step 1: Extract Application

```bash
# Navigate to XAMPP htdocs directory
cd C:\xampp\htdocs  # Windows
# or
cd /Applications/XAMPP/htdocs  # macOS
# or
cd /opt/xampp/htdocs  # Linux

# Extract the application
unzip ITForm-Enterprise-v2.zip
cd ITForm-Enterprise-v2
```

#### Step 2: Create Database

**For SQL Server:**

```sql
-- Open SQL Server Management Studio or SQL Server Query Editor
-- Run the schema.sql file located in database/schema.sql

-- Or manually execute:
sqlcmd -S localhost -U sa -P YourPassword -i database/schema.sql
```

**For MySQL:**

```bash
# Open MySQL terminal
mysql -u root -p

# Create database
CREATE DATABASE itform_db;
USE itform_db;

# Import schema
SOURCE database/schema.sql;
```

#### Step 3: Configure Application

```bash
# Copy .env.example to .env
cp .env.example .env

# Edit .env with your database credentials
nano .env  # Linux/macOS
# or use Notepad on Windows
```

**Sample .env Configuration:**

```env
APP_NAME=ITForm Enterprise
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost/itform

# Database Configuration
DB_CONNECTION=sqlserver
DB_HOST=localhost
DB_PORT=1433
DB_NAME=ITForm_DB
DB_USER=sa
DB_PASS=YourPasswordHere

# Session
SESSION_TIMEOUT=30

# Email Configuration
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@itform.local
MAIL_FROM_NAME=ITForm System
ENABLE_EMAIL_NOTIFICATIONS=true

# Logging
ENABLE_LOGGING=true
LOG_LEVEL=info

# File Upload
MAX_FILE_SIZE_MB=10
APPROVAL_TIMEOUT_DAYS=7
APPROVAL_REMINDER_DAYS=3
```

#### Step 4: Create Required Directories

```bash
# Create storage directories
mkdir -p storage/logs
mkdir -p storage/sessions
mkdir -p storage/uploads
mkdir -p storage/cache
mkdir -p storage/temp

# Set permissions (Linux/macOS)
chmod -R 755 storage
chmod -R 755 public
```

#### Step 5: Create Default Admin User

```bash
# Run the setup script
php setup/create-admin.php

# Follow the prompts to create your first admin account
# Email: admin@itform.local
# Password: (will be prompted)
```

#### Step 6: Access Application

```
URL: http://localhost/itform
Username: admin@itform.local
Password: (as set in Step 5)
```

---

## 📁 Directory Structure

```
ITForm-Enterprise-v2/
├── config/                    # Configuration files
│   ├── app.php               # Main application config
│   ├── database.php          # Database configuration
│   └── constants.php         # Application constants
│
├── src/                       # Source code
│   ├── controllers/          # Request handlers
│   │   ├── RequestController.php
│   │   ├── ApprovalController.php
│   │   ├── AdminController.php
│   │   └── AuthController.php
│   │
│   ├── models/               # Data models
│   │   ├── Request.php
│   │   ├── User.php
│   │   ├── Approval.php
│   │   └── AuditLog.php
│   │
│   ├── views/                # HTML templates
│   │   ├── layouts/
│   │   ├── auth/
│   │   ├── requests/
│   │   └── admin/
│   │
│   ├── services/             # Business logic
│   │   ├── RequestService.php
│   │   ├── ApprovalService.php
│   │   ├── NotificationService.php
│   │   └── PermissionService.php
│   │
│   ├── middleware/           # Middleware classes
│   │   ├── AuthMiddleware.php
│   │   ├── PermissionMiddleware.php
│   │   └── CsrfMiddleware.php
│   │
│   └── utils/                # Utility classes
│       ├── Database.php
│       ├── Validator.php
│       ├── Mailer.php
│       └── Logger.php
│
├── public/                    # Public assets
│   ├── index.php             # Application entry point
│   ├── css/
│   │   ├── style.css         # Main stylesheet
│   │   └── bootstrap.min.css
│   ├── js/
│   │   ├── app.js            # Main application script
│   │   ├── jquery.min.js
│   │   └── bootstrap.min.js
│   └── images/
│
├── database/                  # Database files
│   ├── schema.sql            # Complete database schema
│   └── seeds/                # Sample data
│       ├── users.sql
│       ├── software.sql
│       └── approval_routes.sql
│
├── storage/                   # Runtime storage
│   ├── logs/                 # Application logs
│   ├── sessions/             # Session files
│   ├── uploads/              # User uploads
│   ├── cache/                # Cache files
│   └── temp/                 # Temporary files
│
├── docs/                      # Documentation
│   ├── SDLC_REQUIREMENTS.md  # Complete SDLC
│   ├── API_DOCUMENTATION.md  # API reference
│   ├── USER_MANUAL.md        # User guide
│   ├── ADMIN_GUIDE.md        # Administrator guide
│   └── DEVELOPER_GUIDE.md    # Developer documentation
│
├── tests/                     # Unit and integration tests
│   ├── Unit/
│   ├── Integration/
│   └── Feature/
│
├── setup/                     # Setup scripts
│   ├── create-admin.php      # Create admin user
│   ├── seed-database.php     # Seed sample data
│   └── reset-database.php    # Reset to clean state
│
├── .env.example              # Environment template
├── .htaccess                 # Apache rewrite rules
├── README.md                 # This file
└── LICENSE                   # License file
```

---

## 🔧 Configuration Guide

### Database Configuration

Edit `config/database.php` or use environment variables in `.env`:

```env
# For SQL Server
DB_CONNECTION=sqlserver
DB_HOST=localhost
DB_PORT=1433
DB_NAME=ITForm_DB
DB_USER=sa
DB_PASS=password

# For MySQL
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_NAME=itform_db
DB_USER=root
DB_PASS=password
```

### Email Configuration

Configure SMTP settings for notifications:

```env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@itform.local
ENABLE_EMAIL_NOTIFICATIONS=true
```

### File Upload Configuration

```env
MAX_FILE_SIZE_MB=10
# Allowed extensions: pdf, doc, docx, xls, xlsx, jpg, jpeg, png, gif
```

### Approval Configuration

```env
APPROVAL_TIMEOUT_DAYS=7
APPROVAL_REMINDER_DAYS=3
APPROVAL_AUTO_ESCALATE=true
```

---

## 👥 Default User Accounts

After running setup, you have these default user accounts:

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@itform.local | (Set during setup) |
| IT HOD | ithod@itform.local | ITFORM_HOD_2024 |
| Department HOD | depthead@itform.local | ITFORM_DEPT_2024 |
| IT Personnel | itstaff@itform.local | ITFORM_STAFF_2024 |
| Regular User | user@itform.local | ITFORM_USER_2024 |

**⚠️ Security Note:** Change all default passwords immediately in production!

---

## 🔐 Security Checklist

- [ ] Change all default passwords
- [ ] Set `APP_DEBUG=false` in production
- [ ] Configure HTTPS/SSL certificates
- [ ] Set strong `APP_KEY` in .env
- [ ] Configure firewall rules
- [ ] Restrict database access to application server only
- [ ] Set up automated backups
- [ ] Configure error logging (non-public)
- [ ] Review and configure audit logging
- [ ] Set up email notifications for admin activities

---

## 📚 Core Features

### 1. Request Management
- Create multi-app requests
- Track request status
- View request history
- Download request attachments

### 2. Approval Workflow
- Dynamic, configurable approval routes
- Multi-stage approval process
- Individual app-level approval/rejection
- Approval timeline visualization
- Comments and decision tracking

### 3. Role-Based Access Control
- 10 predefined roles
- Granular permission system
- Dynamic role-permission assignment
- Department and entity-based access

### 4. Notification System
- Email notifications at each approval stage
- Action buttons in email
- In-app notifications
- Notification preferences

### 5. Admin Panel
- Software/App management
- User management
- Department management
- Approval route configuration
- System settings

### 6. Audit & Compliance
- Complete activity logging
- Approval history tracking
- User action audit trail
- Data change history
- Immutable audit logs

### 7. Reporting
- Request status reports
- Approval timeline reports
- User activity reports
- System activity analytics

---

## 🚀 Deployment

### Local Development (XAMPP)

```bash
# Start XAMPP Apache and MySQL/SQL Server
# Access: http://localhost/itform
```

### Production Deployment

1. **Prepare Server**
   - Configure web server (Apache/Nginx)
   - Enable HTTPS/SSL
   - Install required PHP extensions

2. **Upload Application**
   ```bash
   # Use SFTP or git
   sftp user@server.com
   put -r ITForm-Enterprise-v2 /var/www/html/
   ```

3. **Configure Environment**
   ```bash
   # Set appropriate permissions
   chmod 755 /var/www/html/ITForm-Enterprise-v2
   chmod 755 /var/www/html/ITForm-Enterprise-v2/storage
   ```

4. **Setup Database**
   - Create production database
   - Run schema migration
   - Seed initial data

5. **Configure .env for Production**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   # ... other configs
   ```

6. **Test & Monitor**
   - Verify all features
   - Check error logs
   - Monitor system performance

---

## 🐛 Troubleshooting

### Issue: Database Connection Failed

```
Solution:
1. Verify database server is running
2. Check credentials in .env
3. Ensure database has been created
4. Check firewall/network connectivity
```

### Issue: Session Timeout

```
Solution:
1. Check storage/sessions directory exists
2. Verify directory permissions (755)
3. Check SESSION_TIMEOUT in .env
```

### Issue: Email Notifications Not Working

```
Solution:
1. Verify SMTP credentials in .env
2. Check firewall allows SMTP port
3. Review mail logs in storage/logs
4. Ensure ENABLE_EMAIL_NOTIFICATIONS=true
```

### Issue: File Upload Fails

```
Solution:
1. Check storage/uploads directory exists
2. Verify directory permissions (755)
3. Check MAX_FILE_SIZE_MB setting
4. Review allowed file extensions
```

---

## 📞 Support & Documentation

### Documentation Files
- **SDLC_REQUIREMENTS.md** - Complete system requirements
- **API_DOCUMENTATION.md** - API endpoints reference
- **USER_MANUAL.md** - End-user guide
- **ADMIN_GUIDE.md** - Administrator guide
- **DEVELOPER_GUIDE.md** - Developer documentation

### Getting Help
1. Check the documentation files
2. Review error logs in `storage/logs`
3. Check database connection
4. Verify file permissions

---

## 📝 License & Credits

**Version:** 1.0.0  
**Author:** Senior Software Architect  
**Client:** Prime Bisco Nigeria Limited  
**Date:** June 2026  

---

## 🎯 Next Steps

1. ✅ Extract application
2. ✅ Configure database
3. ✅ Set environment variables
4. ✅ Run database schema
5. ✅ Create admin user
6. ✅ Access application
7. ✅ Configure approval routes
8. ✅ Add software/applications
9. ✅ Add users and departments
10. ✅ Configure email notifications

---

**Thank you for using ITForm Enterprise!**

For detailed technical documentation, see the `/docs` folder.


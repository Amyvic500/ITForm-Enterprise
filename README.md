# ITForm Enterprise

## Overview

ITForm Enterprise is an IT request and approval management system built for Prime Bisco Nigeria Limited. It supports multi-application requests, configurable approval workflows, role-based access, notifications, and audit tracking.

**Version:** 1.0.0
**Technology:** PHP 7.1+, SQL Server / MySQL, HTML/CSS/JavaScript

## Quick Start

1. Extract the application into your web server root (for example C:\xampp\htdocs\ITForm).
2. Create the database and run database/schema.sql.
3. Copy .env.example to .env and update database and mail settings.
4. Create required directories:
   - storage/logs
   - storage/sessions
   - storage/uploads
   - storage/cache
   - storage/temp
5. Run php setup/create-admin.php to create the first admin account.
6. Open the application in your browser at http://localhost/itform.

## Requirements

- Apache 2.4+ / XAMPP
- PHP 7.1+ with PDO
- SQL Server 2016+ or MySQL 5.7+
- 500 MB disk space, 2 GB RAM

## Configuration

Use .env for environment-specific settings.

Key settings:
- DB_CONNECTION, DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS
- APP_ENV, APP_DEBUG, APP_URL
- Mail and notification settings for SMTP
- MAX_FILE_SIZE_MB, APPROVAL_TIMEOUT_DAYS, APPROVAL_REMINDER_DAYS

## Core Features

- Request creation and tracking
- Multi-stage approval workflows
- Role-based access control
- Email and in-app notifications
- Admin panel for users, departments, software, and routes
- Audit logging and reporting

## Useful Files

- config/app.php
- config/database.php
- database/schema.sql
- setup/create-admin.php
- docs/USER_MANUAL.md
- docs/ADMIN_GUIDE.md
- docs/DEVELOPER_GUIDE.md

## Support

If you encounter issues:
- Verify .env and database credentials
- Confirm storage/ directories exist and are writable
- Review logs in storage/logs
- Check that your web server and database service are running

## License & Credits

Published for Prime Bisco Nigeria Limited.

For complete installation and usage details, see the files in the docs/ directory.

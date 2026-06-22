# ITForm Enterprise Application - Complete SDLC Documentation

## Executive Summary
ITForm is an enterprise-level IT Request Management System designed for Prime Bisco Nigeria Limited. The application manages complex approval workflows, multi-app requests, role-based access, and comprehensive audit trails.

---

## 1. REQUIREMENTS ANALYSIS

### 1.1 Functional Requirements

#### Core Request Types
- Create New Account
- Delete/Deactivate Account
- Reset Password
- Add Additional Software Access/Features
- Update Account Information
- Add Additional Users to Existing Account
- Modify Permissions/Access Levels

#### Request Submission Details
**User Information Section:**
- Full Name (Required)
- Employee ID (Required)
- Email (Required)
- HOD Email (Required)
- Department (Required)
- Entity (Required)
- Location (Required)
- Worker Type (Required)
- Phone Number (Required)
- Job Title (Required)

**Request Information:**
- Request Type (Required)
- Software/App Name (Required)
- Access Level (Required)
- Justification (Required)
- Attachments (Optional)
- Additional Notes (Optional)

#### Multi-Application Request Logic
- Single request can contain multiple app/software requests
- Each app within a request is processed independently
- Individual app can be approved/rejected at any stage
- Example outcome: SAP (Approved), Google Email (Rejected), CRM (Approved), VPN (Pending)
- Rejection of one app does NOT affect processing of others

#### User Roles & Approval Structure

**9 User Types:**
1. Requestor
2. Approver
3. IT Personnel
4. IT HOD
5. IT MD
6. Super Admin
7. System Admin
8. Department HOD
9. Factory Head/Department MD
10. Direct Supervisor

**Worker Classification & Approval Flow:**

**A. Factory Workers**
- Stage 1: Direct Supervisor/HOD (Approve/Reject)
- Stage 2: Department MD/Factory Head (Approve/Reject)
- Stage 3: IT HOD (Approve/Reject)
- Stage 4: IT MD (Optional - if escalated)

**B. Support Workers (Office Staff)**
- Stage 1: Department HOD (Approve/Reject)
- Stage 2: IT HOD (Approve/Reject)
- Stage 3: IT MD (Optional - if escalated)

**Dynamic Routing:** Approval stages configurable by:
- Entity
- Department
- Location
- Request Type
- Employee Category

#### Termination Rules
1. HOD Rejects → Entire request stops
2. Factory Head Rejects → Only that specific app/item stops
3. IT HOD Rejects → Only that specific app/item stops
4. IT MD Rejects → Only that specific app/item stops
5. Super Admin Override → Can approve/reject/escalate/reassign any request

### 1.2 Non-Functional Requirements

**Performance:**
- Page load time < 2 seconds
- Support 500+ concurrent users
- Database query optimization mandatory

**Security:**
- Role-Based Access Control (RBAC)
- Encryption for sensitive data
- Session management (30-minute timeout)
- Audit logging for all actions
- SQL injection prevention
- CSRF protection
- XSS prevention

**Scalability:**
- Modular architecture
- Stateless request handling
- Database connection pooling

**Usability:**
- Clean, modern interface
- Minimal clicks to complete actions
- Mobile-responsive design
- Intuitive navigation

---

## 2. SYSTEM ARCHITECTURE

### 2.1 Technology Stack
- **Backend:** Pure Native PHP 7.1 (Primary) / CodeIgniter 3 (Alternative)
- **Database:** SQL Server 2016+
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla + jQuery)
- **Server:** XAMPP 3.2.2 (Development), Production-grade server
- **Version Control:** Git

### 2.2 Architecture Pattern: MVC + Service Layer

```
┌─────────────────────────────────────────────────────┐
│                   Web Browser                        │
└────────────────────┬────────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────────┐
│            Controllers (Route Handlers)              │
│  - RequestController                                 │
│  - ApprovalController                                │
│  - AdminController                                   │
│  - AuthController                                    │
└────────────────────┬────────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────────┐
│         Service Layer (Business Logic)               │
│  - RequestService                                    │
│  - ApprovalService                                   │
│  - ApprovalRoutingEngine                             │
│  - NotificationService                               │
│  - PermissionService                                 │
└────────────────────┬────────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────────┐
│            Model Layer (Data Access)                 │
│  - Request                                           │
│  - RequestItem                                       │
│  - Approval                                          │
│  - User                                              │
│  - ApprovalRoute                                     │
│  - AuditLog                                          │
└────────────────────┬────────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────────┐
│         SQL Server Database                          │
│  - Tables, Views, Stored Procedures                  │
└─────────────────────────────────────────────────────┘
```

### 2.3 Key Components

**1. Authentication & Authorization**
- Login/Session management
- Role-based access control
- Permission verification on every request

**2. Request Management**
- Create multi-app requests
- Request status tracking
- Request history

**3. Approval Engine**
- Dynamic routing based on rules
- Multi-stage approval workflow
- Individual app-level approval/rejection
- Comments and audit trail

**4. Admin Panel**
- Software/App Management
- Department Management
- Entity Management
- Location Management
- Approver Management & Route Configuration
- User Management
- Role & Permission Configuration

**5. Notification System**
- Email notifications at each approval stage
- Action buttons in emails
- Request status updates

**6. Audit & Reporting**
- Complete activity log
- Request history
- Approval timeline
- User actions tracking

---

## 3. DATABASE DESIGN

### 3.1 Core Tables (15+ tables)

**tbl_users**
- user_id, email, password, full_name, employee_id, department_id, entity_id, location_id, worker_type, phone, job_title, role_id, is_active, created_at, updated_at

**tbl_roles**
- role_id, role_name, description, is_active

**tbl_role_permissions**
- role_id, permission_id

**tbl_permissions**
- permission_id, permission_name, permission_code, description

**tbl_requests**
- request_id, requestor_id, request_type_id, request_date, current_stage, overall_status, comments, created_at, updated_at

**tbl_request_items**
- item_id, request_id, software_id, access_level_id, justification, attachments, status, created_at, updated_at

**tbl_approvals**
- approval_id, item_id, approver_id, stage, action (APPROVED/REJECTED), comments, decision_date

**tbl_approval_routes**
- route_id, request_type_id, worker_type, stage, approver_role, entity_id, department_id

**tbl_software**
- software_id, software_name, description, category, is_active

**tbl_departments**
- department_id, department_name, hod_id, entity_id

**tbl_entities**
- entity_id, entity_name, description

**tbl_locations**
- location_id, location_name, entity_id

**tbl_request_types**
- type_id, type_name, description

**tbl_audit_logs**
- log_id, user_id, action, table_name, record_id, old_value, new_value, timestamp

**tbl_notifications**
- notification_id, recipient_id, request_id, type, subject, message, read_status, created_at

**tbl_access_levels**
- level_id, level_name, description

---

## 4. UI/UX DESIGN

### 4.1 Key Pages

**Dashboard**
- Pending requests (for approvers)
- My requests (for requestors)
- Request summaries with status

**Request Form**
- Multi-step form
- Section 1: User Information
- Section 2: Request Details
- Section 3: Software/Apps Selection
- Section 4: Justification
- Section 5: Attachments (Optional)

**Approval Page**
- List of pending approvals
- Visual approval workflow timeline
- Individual app approval status
- Comments section
- Action buttons (Approve/Reject)

**Admin Panel**
- Software management
- User management
- Department management
- Approval route configuration
- Reports & Analytics

**Approval Visualization**
```
Request Submitted ↓
   ↓
Stage 1: HOD Review
   ├─ SAP ✓ (Approved)
   ├─ Google Email ✓ (Approved)
   └─ VPN ⏳ (Pending)
   ↓
Stage 2: Factory Head
   ├─ SAP ✓ (Approved)
   ├─ Google Email ✗ (Rejected)
   └─ VPN ✓ (Approved)
   ↓
Stage 3: IT HOD
   ├─ SAP ✓ (Approved)
   ├─ Google Email ✗ (Rejected - FINAL)
   └─ VPN ⏳ (Pending)
```

---

## 5. SECURITY DESIGN

### 5.1 Security Measures

**Authentication:**
- Secure password hashing (bcrypt)
- Session tokens (HTTP-only cookies)
- Login attempt throttling
- Account lockout after failed attempts

**Authorization:**
- Role-based access control
- Permission checking on every sensitive action
- Data filtering based on user roles

**Data Protection:**
- SQL injection prevention (parameterized queries)
- XSS prevention (input/output sanitization)
- CSRF token validation
- Rate limiting

**Audit Trail:**
- Log every action
- Track who made changes and when
- Immutable audit logs

### 5.2 Input Validation
- Server-side validation mandatory
- File upload restrictions
- Maximum file sizes
- Allowed file types

---

## 6. DEPLOYMENT STRATEGY

### 6.1 Local Development (XAMPP)
1. Extract application to xampp/htdocs/itform
2. Configure config/database.php with SQL Server connection
3. Run database schema setup script
4. Access via http://localhost/itform

### 6.2 Production Deployment
1. Use production web server (Apache/Nginx)
2. Enable HTTPS/SSL
3. Configure environment variables
4. Set up automated backups
5. Configure error logging (non-public)
6. Performance optimization:
   - Enable query caching
   - Compress assets
   - CDN for static files

### 6.3 Database Migration
1. SQL Server database setup
2. Create stored procedures for complex queries
3. Indexing on frequently queried columns
4. Backup strategy

---

## 7. TESTING STRATEGY

### 7.1 Test Types
- **Unit Tests:** Individual function testing
- **Integration Tests:** Module interaction
- **System Tests:** End-to-end workflows
- **Acceptance Tests:** Business requirements validation
- **Performance Tests:** Load testing
- **Security Tests:** Penetration testing

### 7.2 Test Cases (Sample)
- Create request with multiple apps
- Approve/reject individual apps
- Verify approval routing based on worker type
- Test role-based access control
- Verify email notifications
- Audit log verification

---

## 8. MAINTENANCE PLAN

### 8.1 Ongoing Support
- Monthly security updates
- Quarterly feature releases
- Quarterly performance reviews
- User support and training

### 8.2 Monitoring
- Error rate monitoring
- Database performance monitoring
- User activity monitoring
- Backup verification

### 8.3 Documentation
- API documentation
- User manual
- Admin guide
- Developer guide
- Troubleshooting guide

---

## 9. PROJECT TIMELINE

**Phase 1: Foundation (Week 1-2)**
- Database setup
- Core authentication system
- Base application structure

**Phase 2: Core Features (Week 3-4)**
- Request creation & management
- Basic approval workflow
- User roles implementation

**Phase 3: Advanced Features (Week 5-6)**
- Dynamic approval routing
- Multi-app request handling
- Notification system

**Phase 4: Admin & Reporting (Week 7-8)**
- Admin panel development
- Reports & analytics
- Configuration management

**Phase 5: Testing & Optimization (Week 9-10)**
- Comprehensive testing
- Performance optimization
- Security hardening

**Phase 6: Documentation & Deployment (Week 11-12)**
- Complete documentation
- User training
- Production deployment

---

## 10. SUCCESS METRICS

- System uptime: 99.5%+
- Average response time: < 2 seconds
- User adoption: > 80%
- Request processing time: < 5 business days
- Error rate: < 0.1%
- User satisfaction: > 4/5 stars

---

**Document Version:** 1.0  
**Last Updated:** June 2026  
**Prepared By:** Senior Software Architect

-- ============================================================================
-- ITForm Enterprise Application - SQL Server Database Schema
-- Version: 1.0
-- Database: ITForm_DB
-- ============================================================================

-- CREATE DATABASE
CREATE DATABASE [ITForm_DB]
GO

USE [ITForm_DB]
GO

-- ============================================================================
-- 1. CORE REFERENCE TABLES
-- ============================================================================

-- Roles Table
CREATE TABLE [tbl_roles] (
    [role_id] INT PRIMARY KEY IDENTITY(1,1),
    [role_name] NVARCHAR(100) NOT NULL UNIQUE,
    [description] NVARCHAR(500),
    [is_active] BIT DEFAULT 1,
    [created_at] DATETIME DEFAULT GETDATE(),
    [updated_at] DATETIME DEFAULT GETDATE()
)

-- Permissions Table
CREATE TABLE [tbl_permissions] (
    [permission_id] INT PRIMARY KEY IDENTITY(1,1),
    [permission_name] NVARCHAR(100) NOT NULL UNIQUE,
    [permission_code] NVARCHAR(50) NOT NULL UNIQUE,
    [description] NVARCHAR(500),
    [created_at] DATETIME DEFAULT GETDATE()
)

-- Role Permissions Junction Table
CREATE TABLE [tbl_role_permissions] (
    [role_id] INT NOT NULL,
    [permission_id] INT NOT NULL,
    PRIMARY KEY ([role_id], [permission_id]),
    FOREIGN KEY ([role_id]) REFERENCES [tbl_roles]([role_id]),
    FOREIGN KEY ([permission_id]) REFERENCES [tbl_permissions]([permission_id])
)

-- Entities Table
CREATE TABLE [tbl_entities] (
    [entity_id] INT PRIMARY KEY IDENTITY(1,1),
    [entity_name] NVARCHAR(150) NOT NULL UNIQUE,
    [description] NVARCHAR(500),
    [is_active] BIT DEFAULT 1,
    [created_at] DATETIME DEFAULT GETDATE(),
    [updated_at] DATETIME DEFAULT GETDATE()
)

-- Locations Table
CREATE TABLE [tbl_locations] (
    [location_id] INT PRIMARY KEY IDENTITY(1,1),
    [location_name] NVARCHAR(150) NOT NULL,
    [entity_id] INT NOT NULL,
    [is_active] BIT DEFAULT 1,
    [created_at] DATETIME DEFAULT GETDATE(),
    [updated_at] DATETIME DEFAULT GETDATE(),
    FOREIGN KEY ([entity_id]) REFERENCES [tbl_entities]([entity_id]),
    UNIQUE([location_name], [entity_id])
)

-- Departments Table
CREATE TABLE [tbl_departments] (
    [department_id] INT PRIMARY KEY IDENTITY(1,1),
    [department_name] NVARCHAR(150) NOT NULL,
    [entity_id] INT NOT NULL,
    [hod_user_id] INT NULL,
    [is_active] BIT DEFAULT 1,
    [created_at] DATETIME DEFAULT GETDATE(),
    [updated_at] DATETIME DEFAULT GETDATE(),
    FOREIGN KEY ([entity_id]) REFERENCES [tbl_entities]([entity_id]),
    UNIQUE([department_name], [entity_id])
)

-- Access Levels Table
CREATE TABLE [tbl_access_levels] (
    [level_id] INT PRIMARY KEY IDENTITY(1,1),
    [level_name] NVARCHAR(100) NOT NULL UNIQUE,
    [description] NVARCHAR(500),
    [level_order] INT,
    [is_active] BIT DEFAULT 1
)

-- Software/Applications Table
CREATE TABLE [tbl_software] (
    [software_id] INT PRIMARY KEY IDENTITY(1,1),
    [software_name] NVARCHAR(150) NOT NULL UNIQUE,
    [description] NVARCHAR(500),
    [category] NVARCHAR(100),
    [requires_approval] BIT DEFAULT 1,
    [is_active] BIT DEFAULT 1,
    [created_at] DATETIME DEFAULT GETDATE(),
    [updated_at] DATETIME DEFAULT GETDATE()
)

-- Request Types Table
CREATE TABLE [tbl_request_types] (
    [type_id] INT PRIMARY KEY IDENTITY(1,1),
    [type_name] NVARCHAR(150) NOT NULL UNIQUE,
    [description] NVARCHAR(500),
    [approval_required] BIT DEFAULT 1,
    [is_active] BIT DEFAULT 1
)

-- Worker Types Table
CREATE TABLE [tbl_worker_types] (
    [worker_type_id] INT PRIMARY KEY IDENTITY(1,1),
    [worker_type_name] NVARCHAR(100) NOT NULL UNIQUE,
    [description] NVARCHAR(500)
)

-- ============================================================================
-- 2. USER MANAGEMENT TABLES
-- ============================================================================

-- Users Table
CREATE TABLE [tbl_users] (
    [user_id] INT PRIMARY KEY IDENTITY(1,1),
    [email] NVARCHAR(150) NOT NULL UNIQUE,
    [password_hash] NVARCHAR(255) NOT NULL,
    [full_name] NVARCHAR(150) NOT NULL,
    [employee_id] NVARCHAR(50) NOT NULL UNIQUE,
    [phone] NVARCHAR(20),
    [job_title] NVARCHAR(150),
    [department_id] INT NOT NULL,
    [entity_id] INT NOT NULL,
    [location_id] INT NOT NULL,
    [worker_type_id] INT NOT NULL,
    [role_id] INT NOT NULL,
    [is_active] BIT DEFAULT 1,
    [last_login] DATETIME NULL,
    [login_attempts] INT DEFAULT 0,
    [locked_until] DATETIME NULL,
    [created_at] DATETIME DEFAULT GETDATE(),
    [updated_at] DATETIME DEFAULT GETDATE(),
    FOREIGN KEY ([department_id]) REFERENCES [tbl_departments]([department_id]),
    FOREIGN KEY ([entity_id]) REFERENCES [tbl_entities]([entity_id]),
    FOREIGN KEY ([location_id]) REFERENCES [tbl_locations]([location_id]),
    FOREIGN KEY ([worker_type_id]) REFERENCES [tbl_worker_types]([worker_type_id]),
    FOREIGN KEY ([role_id]) REFERENCES [tbl_roles]([role_id])
)

-- Create index on email for faster login lookups
CREATE INDEX [idx_users_email] ON [tbl_users]([email])
CREATE INDEX [idx_users_employee_id] ON [tbl_users]([employee_id])

-- User Approver Assignments Table
CREATE TABLE [tbl_user_approvers] (
    [id] INT PRIMARY KEY IDENTITY(1,1),
    [user_id] INT NOT NULL,
    [approver_user_id] INT NOT NULL,
    [approval_stage] INT NOT NULL,
    [is_active] BIT DEFAULT 1,
    [created_at] DATETIME DEFAULT GETDATE(),
    FOREIGN KEY ([user_id]) REFERENCES [tbl_users]([user_id]),
    FOREIGN KEY ([approver_user_id]) REFERENCES [tbl_users]([user_id]),
    UNIQUE([user_id], [approver_user_id], [approval_stage])
)

-- ============================================================================
-- 3. REQUEST MANAGEMENT TABLES
-- ============================================================================

-- Requests Table
CREATE TABLE [tbl_requests] (
    [request_id] INT PRIMARY KEY IDENTITY(1,1),
    [request_number] NVARCHAR(50) NOT NULL UNIQUE,
    [requestor_id] INT NOT NULL,
    [request_type_id] INT NOT NULL,
    [department_id] INT NOT NULL,
    [entity_id] INT NOT NULL,
    [location_id] INT NOT NULL,
    [current_stage] INT DEFAULT 1,
    [overall_status] NVARCHAR(50) DEFAULT 'PENDING', -- PENDING, IN_PROGRESS, APPROVED, REJECTED, COMPLETED
    [rejection_reason] NVARCHAR(MAX) NULL,
    [additional_comments] NVARCHAR(MAX),
    [created_at] DATETIME DEFAULT GETDATE(),
    [updated_at] DATETIME DEFAULT GETDATE(),
    [submitted_at] DATETIME NULL,
    [completed_at] DATETIME NULL,
    FOREIGN KEY ([requestor_id]) REFERENCES [tbl_users]([user_id]),
    FOREIGN KEY ([request_type_id]) REFERENCES [tbl_request_types]([type_id]),
    FOREIGN KEY ([department_id]) REFERENCES [tbl_departments]([department_id]),
    FOREIGN KEY ([entity_id]) REFERENCES [tbl_entities]([entity_id]),
    FOREIGN KEY ([location_id]) REFERENCES [tbl_locations]([location_id])
)

CREATE INDEX [idx_requests_requestor] ON [tbl_requests]([requestor_id])
CREATE INDEX [idx_requests_status] ON [tbl_requests]([overall_status])
CREATE INDEX [idx_requests_created] ON [tbl_requests]([created_at])

-- Request Items Table (Individual apps within a request)
CREATE TABLE [tbl_request_items] (
    [item_id] INT PRIMARY KEY IDENTITY(1,1),
    [request_id] INT NOT NULL,
    [software_id] INT NOT NULL,
    [access_level_id] INT NOT NULL,
    [justification] NVARCHAR(MAX) NOT NULL,
    [item_status] NVARCHAR(50) DEFAULT 'PENDING', -- PENDING, APPROVED, REJECTED, COMPLETED
    [rejection_reason] NVARCHAR(MAX) NULL,
    [created_at] DATETIME DEFAULT GETDATE(),
    [updated_at] DATETIME DEFAULT GETDATE(),
    FOREIGN KEY ([request_id]) REFERENCES [tbl_requests]([request_id]) ON DELETE CASCADE,
    FOREIGN KEY ([software_id]) REFERENCES [tbl_software]([software_id]),
    FOREIGN KEY ([access_level_id]) REFERENCES [tbl_access_levels]([level_id])
)

CREATE INDEX [idx_request_items_request] ON [tbl_request_items]([request_id])
CREATE INDEX [idx_request_items_status] ON [tbl_request_items]([item_status])

-- Request Attachments Table
CREATE TABLE [tbl_request_attachments] (
    [attachment_id] INT PRIMARY KEY IDENTITY(1,1),
    [request_id] INT NOT NULL,
    [file_name] NVARCHAR(255) NOT NULL,
    [file_path] NVARCHAR(500) NOT NULL,
    [file_size] INT,
    [uploaded_by] INT NOT NULL,
    [uploaded_at] DATETIME DEFAULT GETDATE(),
    FOREIGN KEY ([request_id]) REFERENCES [tbl_requests]([request_id]) ON DELETE CASCADE,
    FOREIGN KEY ([uploaded_by]) REFERENCES [tbl_users]([user_id])
)

-- ============================================================================
-- 4. APPROVAL WORKFLOW TABLES
-- ============================================================================

-- Approval Routes Configuration Table
CREATE TABLE [tbl_approval_routes] (
    [route_id] INT PRIMARY KEY IDENTITY(1,1),
    [request_type_id] INT NOT NULL,
    [worker_type_id] INT NOT NULL,
    [stage_number] INT NOT NULL,
    [approver_role_id] INT NOT NULL,
    [entity_id] INT NULL,
    [department_id] INT NULL,
    [is_optional] BIT DEFAULT 0,
    [is_active] BIT DEFAULT 1,
    [created_at] DATETIME DEFAULT GETDATE(),
    [updated_at] DATETIME DEFAULT GETDATE(),
    FOREIGN KEY ([request_type_id]) REFERENCES [tbl_request_types]([type_id]),
    FOREIGN KEY ([worker_type_id]) REFERENCES [tbl_worker_types]([worker_type_id]),
    FOREIGN KEY ([approver_role_id]) REFERENCES [tbl_roles]([role_id]),
    FOREIGN KEY ([entity_id]) REFERENCES [tbl_entities]([entity_id]),
    FOREIGN KEY ([department_id]) REFERENCES [tbl_departments]([department_id]),
    UNIQUE([request_type_id], [worker_type_id], [stage_number], [approver_role_id], [entity_id], [department_id])
)

-- Approvals Table
CREATE TABLE [tbl_approvals] (
    [approval_id] INT PRIMARY KEY IDENTITY(1,1),
    [item_id] INT NOT NULL,
    [approver_id] INT NOT NULL,
    [approval_stage] INT NOT NULL,
    [approval_action] NVARCHAR(50) NOT NULL, -- APPROVED, REJECTED, PENDING, ESCALATED
    [approval_comments] NVARCHAR(MAX),
    [decision_date] DATETIME NULL,
    [created_at] DATETIME DEFAULT GETDATE(),
    [updated_at] DATETIME DEFAULT GETDATE(),
    FOREIGN KEY ([item_id]) REFERENCES [tbl_request_items]([item_id]) ON DELETE CASCADE,
    FOREIGN KEY ([approver_id]) REFERENCES [tbl_users]([user_id])
)

CREATE INDEX [idx_approvals_item] ON [tbl_approvals]([item_id])
CREATE INDEX [idx_approvals_approver] ON [tbl_approvals]([approver_id])
CREATE INDEX [idx_approvals_stage] ON [tbl_approvals]([approval_stage])

-- Approval History Table
CREATE TABLE [tbl_approval_history] (
    [history_id] INT PRIMARY KEY IDENTITY(1,1),
    [item_id] INT NOT NULL,
    [approval_stage] INT NOT NULL,
    [expected_approver_role_id] INT NOT NULL,
    [assigned_approver_id] INT NULL,
    [actual_approver_id] INT NULL,
    [status] NVARCHAR(50) DEFAULT 'PENDING',
    [assigned_at] DATETIME,
    [completed_at] DATETIME NULL,
    FOREIGN KEY ([item_id]) REFERENCES [tbl_request_items]([item_id]) ON DELETE CASCADE,
    FOREIGN KEY ([expected_approver_role_id]) REFERENCES [tbl_roles]([role_id]),
    FOREIGN KEY ([assigned_approver_id]) REFERENCES [tbl_users]([user_id]),
    FOREIGN KEY ([actual_approver_id]) REFERENCES [tbl_users]([user_id])
)

-- ============================================================================
-- 5. NOTIFICATION & COMMUNICATION TABLES
-- ============================================================================

-- Notifications Table
CREATE TABLE [tbl_notifications] (
    [notification_id] INT PRIMARY KEY IDENTITY(1,1),
    [recipient_id] INT NOT NULL,
    [request_id] INT NOT NULL,
    [item_id] INT NULL,
    [notification_type] NVARCHAR(50) NOT NULL, -- REQUEST_CREATED, APPROVAL_REQUIRED, APPROVED, REJECTED, etc.
    [subject] NVARCHAR(255) NOT NULL,
    [message] NVARCHAR(MAX),
    [is_read] BIT DEFAULT 0,
    [read_at] DATETIME NULL,
    [created_at] DATETIME DEFAULT GETDATE(),
    FOREIGN KEY ([recipient_id]) REFERENCES [tbl_users]([user_id]),
    FOREIGN KEY ([request_id]) REFERENCES [tbl_requests]([request_id]),
    FOREIGN KEY ([item_id]) REFERENCES [tbl_request_items]([item_id])
)

CREATE INDEX [idx_notifications_recipient] ON [tbl_notifications]([recipient_id])
CREATE INDEX [idx_notifications_read] ON [tbl_notifications]([is_read])

-- Email Logs Table
CREATE TABLE [tbl_email_logs] (
    [log_id] INT PRIMARY KEY IDENTITY(1,1),
    [recipient_email] NVARCHAR(150) NOT NULL,
    [subject] NVARCHAR(255) NOT NULL,
    [body] NVARCHAR(MAX),
    [status] NVARCHAR(50), -- SENT, FAILED, PENDING
    [error_message] NVARCHAR(MAX) NULL,
    [sent_at] DATETIME NULL,
    [created_at] DATETIME DEFAULT GETDATE()
)

-- ============================================================================
-- 6. AUDIT & LOGGING TABLES
-- ============================================================================

-- Audit Logs Table
CREATE TABLE [tbl_audit_logs] (
    [log_id] INT PRIMARY KEY IDENTITY(1,1),
    [user_id] INT NOT NULL,
    [action] NVARCHAR(100) NOT NULL,
    [table_name] NVARCHAR(100),
    [record_id] INT NULL,
    [old_value] NVARCHAR(MAX),
    [new_value] NVARCHAR(MAX),
    [ip_address] NVARCHAR(50),
    [user_agent] NVARCHAR(500),
    [created_at] DATETIME DEFAULT GETDATE(),
    FOREIGN KEY ([user_id]) REFERENCES [tbl_users]([user_id])
)

CREATE INDEX [idx_audit_logs_user] ON [tbl_audit_logs]([user_id])
CREATE INDEX [idx_audit_logs_action] ON [tbl_audit_logs]([action])
CREATE INDEX [idx_audit_logs_created] ON [tbl_audit_logs]([created_at])

-- Activity Logs Table
CREATE TABLE [tbl_activity_logs] (
    [activity_id] INT PRIMARY KEY IDENTITY(1,1),
    [user_id] INT,
    [request_id] INT,
    [activity_type] NVARCHAR(100),
    [description] NVARCHAR(MAX),
    [status_before] NVARCHAR(50),
    [status_after] NVARCHAR(50),
    [created_at] DATETIME DEFAULT GETDATE(),
    FOREIGN KEY ([user_id]) REFERENCES [tbl_users]([user_id]),
    FOREIGN KEY ([request_id]) REFERENCES [tbl_requests]([request_id])
)

CREATE INDEX [idx_activity_logs_request] ON [tbl_activity_logs]([request_id])
CREATE INDEX [idx_activity_logs_created] ON [tbl_activity_logs]([created_at])

-- ============================================================================
-- 7. SYSTEM CONFIGURATION TABLES
-- ============================================================================

-- System Settings Table
CREATE TABLE [tbl_system_settings] (
    [setting_id] INT PRIMARY KEY IDENTITY(1,1),
    [setting_key] NVARCHAR(100) NOT NULL UNIQUE,
    [setting_value] NVARCHAR(MAX),
    [setting_type] NVARCHAR(50), -- STRING, BOOLEAN, INTEGER, JSON
    [description] NVARCHAR(500),
    [updated_at] DATETIME DEFAULT GETDATE()
)

-- Email Settings Table
CREATE TABLE [tbl_email_settings] (
    [id] INT PRIMARY KEY IDENTITY(1,1),
    [smtp_host] NVARCHAR(255),
    [smtp_port] INT,
    [smtp_username] NVARCHAR(255),
    [smtp_password] NVARCHAR(255),
    [from_email] NVARCHAR(150),
    [from_name] NVARCHAR(150),
    [is_active] BIT DEFAULT 1,
    [updated_at] DATETIME DEFAULT GETDATE()
)

-- ============================================================================
-- 8. INDEXES FOR PERFORMANCE
-- ============================================================================

-- Additional indexes for frequently used queries
CREATE INDEX [idx_requests_department] ON [tbl_requests]([department_id])
CREATE INDEX [idx_requests_entity] ON [tbl_requests]([entity_id])
CREATE INDEX [idx_users_department] ON [tbl_users]([department_id])
CREATE INDEX [idx_users_role] ON [tbl_users]([role_id])

-- ============================================================================
-- 9. VIEWS FOR COMMON QUERIES
-- ============================================================================

-- View: Pending Approvals for Approvers
CREATE VIEW [v_pending_approvals] AS
SELECT 
    ri.[item_id],
    r.[request_id],
    r.[request_number],
    u_requestor.[full_name] AS [requestor_name],
    r.[request_type_id],
    rt.[type_name] AS [request_type],
    s.[software_name],
    ri.[item_status],
    al.[level_name] AS [access_level],
    r.[created_at],
    r.[overall_status]
FROM [tbl_request_items] ri
JOIN [tbl_requests] r ON ri.[request_id] = r.[request_id]
JOIN [tbl_users] u_requestor ON r.[requestor_id] = u_requestor.[user_id]
JOIN [tbl_request_types] rt ON r.[request_type_id] = rt.[type_id]
JOIN [tbl_software] s ON ri.[software_id] = s.[software_id]
JOIN [tbl_access_levels] al ON ri.[access_level_id] = al.[level_id]
WHERE ri.[item_status] = 'PENDING'
  AND r.[overall_status] IN ('PENDING', 'IN_PROGRESS')

-- View: Request Status Summary
CREATE VIEW [v_request_status_summary] AS
SELECT 
    r.[request_id],
    r.[request_number],
    u.[full_name] AS [requestor_name],
    r.[overall_status],
    COUNT(ri.[item_id]) AS [total_items],
    SUM(CASE WHEN ri.[item_status] = 'APPROVED' THEN 1 ELSE 0 END) AS [approved_items],
    SUM(CASE WHEN ri.[item_status] = 'REJECTED' THEN 1 ELSE 0 END) AS [rejected_items],
    SUM(CASE WHEN ri.[item_status] = 'PENDING' THEN 1 ELSE 0 END) AS [pending_items],
    r.[created_at],
    r.[updated_at]
FROM [tbl_requests] r
JOIN [tbl_users] u ON r.[requestor_id] = u.[user_id]
LEFT JOIN [tbl_request_items] ri ON r.[request_id] = ri.[request_id]
GROUP BY r.[request_id], r.[request_number], u.[full_name], r.[overall_status], r.[created_at], r.[updated_at]

-- ============================================================================
-- 10. STORED PROCEDURES FOR COMPLEX OPERATIONS
-- ============================================================================

-- Stored Procedure: Get Dynamic Approval Route
CREATE PROCEDURE [sp_get_approval_route]
    @request_type_id INT,
    @worker_type_id INT,
    @entity_id INT = NULL,
    @department_id INT = NULL
AS
BEGIN
    SET NOCOUNT ON
    
    SELECT 
        [route_id],
        [request_type_id],
        [worker_type_id],
        [stage_number],
        [approver_role_id],
        [is_optional],
        [is_active]
    FROM [tbl_approval_routes]
    WHERE [request_type_id] = @request_type_id
      AND [worker_type_id] = @worker_type_id
      AND [is_active] = 1
      AND ([entity_id] IS NULL OR [entity_id] = @entity_id)
      AND ([department_id] IS NULL OR [department_id] = @department_id)
    ORDER BY [stage_number]
END

-- Stored Procedure: Create Request Number
CREATE PROCEDURE [sp_generate_request_number]
    @request_number NVARCHAR(50) OUTPUT
AS
BEGIN
    SET NOCOUNT ON
    
    DECLARE @year NVARCHAR(4) = YEAR(GETDATE())
    DECLARE @month NVARCHAR(2) = FORMAT(MONTH(GETDATE()), '00')
    DECLARE @count INT
    
    SELECT @count = COUNT(*) + 1
    FROM [tbl_requests]
    WHERE [request_number] LIKE @year + @month + '%'
    
    SET @request_number = @year + @month + '-' + FORMAT(@count, '0000')
END

-- Stored Procedure: Update Request Status
CREATE PROCEDURE [sp_update_request_status]
    @request_id INT
AS
BEGIN
    SET NOCOUNT ON
    
    DECLARE @approved_count INT
    DECLARE @rejected_count INT
    DECLARE @pending_count INT
    DECLARE @total_count INT
    
    SELECT 
        @approved_count = SUM(CASE WHEN [item_status] = 'APPROVED' THEN 1 ELSE 0 END),
        @rejected_count = SUM(CASE WHEN [item_status] = 'REJECTED' THEN 1 ELSE 0 END),
        @pending_count = SUM(CASE WHEN [item_status] = 'PENDING' THEN 1 ELSE 0 END),
        @total_count = COUNT(*)
    FROM [tbl_request_items]
    WHERE [request_id] = @request_id
    
    DECLARE @new_status NVARCHAR(50)
    
    IF @rejected_count > 0
        SET @new_status = 'REJECTED'
    ELSE IF @pending_count > 0
        SET @new_status = 'IN_PROGRESS'
    ELSE IF @approved_count = @total_count AND @total_count > 0
        SET @new_status = 'APPROVED'
    ELSE
        SET @new_status = 'PENDING'
    
    UPDATE [tbl_requests]
    SET [overall_status] = @new_status,
        [updated_at] = GETDATE()
    WHERE [request_id] = @request_id
END

-- ============================================================================
-- 11. INITIAL DATA SETUP
-- ============================================================================

-- Insert Default Roles
INSERT INTO [tbl_roles] ([role_name], [description]) VALUES
('Super Admin', 'Full system access and configuration'),
('System Admin', 'System administration and maintenance'),
('IT HOD', 'IT Head of Department - Senior approver'),
('IT MD', 'IT Managing Director - Final approver'),
('IT Personnel', 'IT support staff'),
('Department HOD', 'Department Head - First level approver'),
('Factory Head', 'Factory/Location Head - Second level approver'),
('Direct Supervisor', 'Direct supervisor of employees'),
('Approver', 'Request approver'),
('Requestor', 'Regular user/request creator')

-- Insert Default Permissions
INSERT INTO [tbl_permissions] ([permission_name], [permission_code], [description]) VALUES
('Create Request', 'CREATE_REQUEST', 'Ability to create new requests'),
('View Own Requests', 'VIEW_OWN_REQUESTS', 'View own submitted requests'),
('View All Requests', 'VIEW_ALL_REQUESTS', 'View all requests in system'),
('Approve Requests', 'APPROVE_REQUESTS', 'Ability to approve requests'),
('Reject Requests', 'REJECT_REQUESTS', 'Ability to reject requests'),
('Manage Software', 'MANAGE_SOFTWARE', 'Add/edit/delete software'),
('Manage Users', 'MANAGE_USERS', 'User management'),
('Manage Departments', 'MANAGE_DEPARTMENTS', 'Department management'),
('Manage Entities', 'MANAGE_ENTITIES', 'Entity management'),
('Configure Routes', 'CONFIGURE_ROUTES', 'Approval route configuration'),
('View Reports', 'VIEW_REPORTS', 'Access to reporting module'),
('View Audit Logs', 'VIEW_AUDIT_LOGS', 'Access to audit trails'),
('System Configuration', 'SYSTEM_CONFIG', 'System settings and configuration')

-- Insert Worker Types
INSERT INTO [tbl_worker_types] ([worker_type_name], [description]) VALUES
('Factory Worker', 'Production floor workers'),
('Office Staff', 'Administrative and office staff'),
('Management', 'Management level employees'),
('Contract Staff', 'Contract or temporary staff')

-- Insert Access Levels
INSERT INTO [tbl_access_levels] ([level_name], [description], [level_order]) VALUES
('Read Only', 'View only access', 1),
('Standard User', 'Standard user access', 2),
('Power User', 'Enhanced user access', 3),
('Administrator', 'Administrative access', 4)

-- Insert Request Types
INSERT INTO [tbl_request_types] ([type_name], [description], [approval_required]) VALUES
('Create New Account', 'Request for new user account creation', 1),
('Delete Account', 'Request to delete or deactivate account', 1),
('Reset Password', 'Password reset request', 0),
('Add Software Access', 'Request for additional software access', 1),
('Modify Access Level', 'Change access level for existing account', 1),
('Add User to Account', 'Add additional user to existing account', 1),
('Update Account Info', 'Update account information', 1)

-- Insert Default Entity
INSERT INTO [tbl_entities] ([entity_name], [description]) VALUES
('Prime Bisco Nigeria Limited', 'Main corporate entity')

-- Insert Default System Settings
INSERT INTO [tbl_system_settings] ([setting_key], [setting_value], [setting_type]) VALUES
('APP_NAME', 'ITForm Enterprise', 'STRING'),
('APP_VERSION', '1.0.0', 'STRING'),
('APPROVAL_TIMEOUT_DAYS', '7', 'INTEGER'),
('SESSION_TIMEOUT_MINUTES', '30', 'INTEGER'),
('MAX_FILE_SIZE_MB', '10', 'INTEGER'),
('ENABLE_EMAIL_NOTIFICATIONS', '1', 'BOOLEAN'),
('ENABLE_SMS_NOTIFICATIONS', '0', 'BOOLEAN')

-- ============================================================================
-- End of Database Schema
-- ============================================================================

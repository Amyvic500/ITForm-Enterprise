<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITForm Enterprise - Submit IT Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .header p {
            color: #999;
            font-size: 16px;
        }
        .admin-link {
            position: absolute;
            top: 20px;
            right: 20px;
            background: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            color: #667eea;
            font-weight: 600;
        }
        .form-section {
            margin-bottom: 30px;
        }
        .form-section h3 {
            color: #667eea;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        .form-control, .form-select {
            border-radius: 5px;
            padding: 12px;
            margin-bottom: 15px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 14px 40px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            color: white;
        }
        .required {
            color: #dc3545;
        }
        .success-message {
            display: none;
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .error-message {
            display: none;
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <a href="?url=/admin/login" class="admin-link">Admin Login</a>

    <div class="container" style="max-width: 900px;">
        <div class="header">
            <h1>🔐 ITForm Enterprise</h1>
            <p>Submit Your IT Request Here</p>
        </div>

        <div id="successMessage" class="success-message">
            ✅ Your request has been submitted successfully! Request ID: <span id="requestId"></span>
        </div>

        <div id="errorMessage" class="error-message">
            ❌ <span id="errorText"></span>
        </div>

        <form id="requestForm">
            <!-- Requester Information -->
            <div class="form-section">
                <h3>📋 Requester Information</h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">First Name <span class="required">*</span></label>
                        <input type="text" class="form-control" name="first_name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name <span class="required">*</span></label>
                        <input type="text" class="form-control" name="last_name" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Department <span class="required">*</span></label>
                        <select class="form-select" name="department_id" required>
                            <option value="">Select Department</option>
                            <option value="1">IT</option>
                            <option value="2">Finance</option>
                            <option value="3">HR</option>
                            <option value="4">Operations</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Request Details -->
            <div class="form-section">
                <h3>📝 Request Details</h3>

                <label class="form-label">Request Type <span class="required">*</span></label>
                <select class="form-select" name="request_type" required>
                    <option value="">Select Type</option>
                    <option value="software">Software Installation</option>
                    <option value="hardware">Hardware Request</option>
                    <option value="access">System Access</option>
                    <option value="email">Email/Account Setup</option>
                    <option value="vpn">VPN Access</option>
                    <option value="printer">Printer Setup</option>
                    <option value="other">Other</option>
                </select>

                <label class="form-label mt-3">Description <span class="required">*</span></label>
                <textarea class="form-control" name="description" rows="5" placeholder="Please describe your request in detail..." required></textarea>

                <label class="form-label mt-3">Priority <span class="required">*</span></label>
                <select class="form-select" name="priority" required>
                    <option value="">Select Priority</option>
                    <option value="low">Low - Can wait</option>
                    <option value="medium">Medium - Standard</option>
                    <option value="high">High - Urgent</option>
                </select>
            </div>

            <!-- Additional Information -->
            <div class="form-section">
                <h3>ℹ️ Additional Information</h3>

                <label class="form-label">Expected Date Needed <span class="required">*</span></label>
                <input type="date" class="form-control" name="due_date" required>

                <label class="form-label mt-3">Comments (Optional)</label>
                <textarea class="form-control" name="comments" rows="3" placeholder="Any additional comments..."></textarea>
            </div>

            <button type="submit" class="btn-submit">Submit Request</button>
        </form>
    </div>

    <script>
        document.getElementById('requestForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);
            
            try {
                const response = await fetch('?url=/api/requests/create', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    document.getElementById('successMessage').style.display = 'block';
                    document.getElementById('requestId').textContent = result.data.request_id;
                    document.getElementById('requestForm').reset();
                } else {
                    showError(result.message);
                }
            } catch (error) {
                showError('Error submitting request: ' + error.message);
            }
        });
        
        function showError(message) {
            document.getElementById('errorMessage').style.display = 'block';
            document.getElementById('errorText').textContent = message;
        }
    </script>
</body>
</html>

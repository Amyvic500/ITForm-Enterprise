<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Request - ITForm Enterprise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .sidebar { background: white; border-right: 1px solid #e0e0e0; min-height: calc(100vh - 60px); padding: 20px 0; }
        .sidebar a { display: block; padding: 12px 20px; color: #333; text-decoration: none; border-left: 3px solid transparent; transition: all 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #f5f7fa; border-left-color: #667eea; color: #667eea; }
        .main-content { padding: 30px; }
        .card { border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="?url=/itform/dashboard">🔐 ITForm Enterprise</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['user_full_name'] ?? 'User'); ?></span></li>
                <li class="nav-item"><a class="nav-link" href="?url=/itform/auth/logout">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="row g-0">
        <div class="col-md-3">
            <div class="sidebar">
                <a href="?url=/itform/dashboard">📊 Dashboard</a>
                <a href="?url=/itform/requests">📋 My Requests</a>
                <a href="?url=/itform/requests/create" class="active">➕ New Request</a>
                <a href="?url=/itform/approvals">✅ Pending Approvals</a>
                <hr>
                <a href="?url=/itform/auth/logout">🚪 Logout</a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="main-content">
                <h1>Create New Request</h1>

                <div class="card mt-4">
                    <div class="card-header"><h5 style="margin: 0;">Request Details</h5></div>
                    <div class="card-body">
                        <form id="requestForm">
                            <div class="mb-3">
                                <label for="title" class="form-label">Request Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">Request Type</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="software">Software Installation</option>
                                    <option value="hardware">Hardware Request</option>
                                    <option value="access">Access Request</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit Request</button>
                            <a href="?url=/itform/requests" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

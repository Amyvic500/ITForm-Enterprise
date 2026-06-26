<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Requests - ITForm Enterprise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .navbar-brand { font-weight: 700; font-size: 20px; }
        .sidebar { background: white; border-right: 1px solid #e0e0e0; min-height: calc(100vh - 60px); padding: 20px 0; }
        .sidebar a { display: block; padding: 12px 20px; color: #333; text-decoration: none; border-left: 3px solid transparent; transition: all 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #f5f7fa; border-left-color: #667eea; color: #667eea; }
        .main-content { padding: 30px; }
        .card { border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; }
        .badge-pending { background: #ffc107; } .badge-approved { background: #28a745; } .badge-rejected { background: #dc3545; }
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
                <a href="?url=/itform/requests" class="active">📋 My Requests</a>
                <a href="?url=/itform/requests/create">➕ New Request</a>
                <a href="?url=/itform/approvals">✅ Pending Approvals</a>
                <hr>
                <a href="?url=/itform/auth/logout">🚪 Logout</a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="main-content">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                    <h1>My Requests</h1>
                    <a href="?url=/itform/requests/create" class="btn btn-primary">+ New Request</a>
                </div>

                <div class="card">
                    <div class="card-header"><h5 style="margin: 0;">All Requests</h5></div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No requests found. <a href="?url=/itform/requests/create">Create one now</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

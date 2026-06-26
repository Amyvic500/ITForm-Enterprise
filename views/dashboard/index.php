<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ITForm Enterprise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 20px;
        }
        .sidebar {
            background: white;
            border-right: 1px solid #e0e0e0;
            min-height: calc(100vh - 60px);
            padding: 20px 0;
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background: #f5f7fa;
            border-left-color: #667eea;
            color: #667eea;
        }
        .main-content {
            padding: 30px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 20px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
            margin-bottom: 20px;
        }
        .stat-box h3 {
            color: #667eea;
            font-weight: 700;
        }
        .stat-box p {
            color: #999;
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="?url=/itform/dashboard">🔐 ITForm Enterprise</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['user_full_name'] ?? 'User'); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?url=/itform/auth/logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="row g-0">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="sidebar">
                <a href="?url=/itform/dashboard" class="active">📊 Dashboard</a>
                <a href="?url=/itform/requests">📋 My Requests</a>
                <a href="?url=/itform/requests/create">➕ New Request</a>
                <a href="?url=/itform/approvals">✅ Pending Approvals</a>
                <hr>
                <a href="?url=/itform/auth/logout">🚪 Logout</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="main-content">
                <h1>Welcome to ITForm Enterprise</h1>
                <p class="text-muted">Manage your IT requests and approvals efficiently</p>

                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-box">
                            <h3>0</h3>
                            <p>Pending Requests</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <h3>0</h3>
                            <p>Approvals Needed</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <h3>0</h3>
                            <p>Completed</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <h3>0</h3>
                            <p>Rejected</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 style="margin: 0;">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <a href="?url=/itform/requests/create" class="btn btn-primary">Create New Request</a>
                        <a href="?url=/itform/requests" class="btn btn-secondary">View All Requests</a>
                        <a href="?url=/itform/approvals" class="btn btn-info">View Approvals</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 style="margin: 0;">Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">No recent activity</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

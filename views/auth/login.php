<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITForm Enterprise - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }
        .login-container h1 {
            text-align: center;
            margin-bottom: 10px;
            color: #333;
            font-weight: 700;
        }
        .login-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .form-control {
            border-radius: 5px;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            color: white;
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
            text-align: center;
            padding: 10px;
            background: #f8d7da;
            border-radius: 5px;
            display: none;
        }
        .error-message.show {
            display: block;
        }
        .loading {
            display: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>🔐 ITForm</h1>
        <p class="login-subtitle">Enterprise Request Management System</p>
        
        <div id="errorMessage" class="error-message"></div>
        
        <form id="loginForm">
            <input 
                type="email" 
                class="form-control" 
                id="email" 
                placeholder="Email Address" 
                required
                autocomplete="email"
            >
            
            <input 
                type="password" 
                class="form-control" 
                id="password" 
                placeholder="Password" 
                required
                autocomplete="current-password"
            >
            
            <button type="submit" class="btn-login">
                <span class="normal">Login</span>
                <span class="loading" style="display: none;">Logging in...</span>
            </button>
        </form>
        
        <hr style="margin: 20px 0;">
        <p style="text-align: center; color: #999; font-size: 12px;">
            Demo: victoria@itform.test / Victoria@2026
        </p>
    </div>

    <script>
        const form = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const errorDiv = document.getElementById('errorMessage');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const email = emailInput.value.trim();
            const password = passwordInput.value;
            
            if (!email || !password) {
                showError('Please fill in all fields');
                return;
            }
            
            submitBtn.disabled = true;
            submitBtn.querySelector('.normal').style.display = 'none';
            submitBtn.querySelector('.loading').style.display = 'inline';
            
            try {
                const response = await fetch('?url=/itform/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    showError('', false);
                    setTimeout(() => {
                        window.location.href = '?url=/itform/dashboard';
                    }, 500);
                } else {
                    showError(data.message || data.data?.message || 'Login failed');
                    submitBtn.disabled = false;
                    submitBtn.querySelector('.normal').style.display = 'inline';
                    submitBtn.querySelector('.loading').style.display = 'none';
                }
            } catch (error) {
                showError('Connection error: ' + error.message);
                submitBtn.disabled = false;
                submitBtn.querySelector('.normal').style.display = 'inline';
                submitBtn.querySelector('.loading').style.display = 'none';
            }
        });
        
        function showError(message, show = true) {
            if (show && message) {
                errorDiv.textContent = message;
                errorDiv.classList.add('show');
            } else {
                errorDiv.classList.remove('show');
            }
        }
    </script>
</body>
</html>

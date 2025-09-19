<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login - E-Commerce Platform</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Animate CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2E86AB;
            --primary-hover: #1B5E7A;
            --accent-color: #F18F01;
            --secondary-color: #6c757d;
            --light-blue: #E3F2FD;
            --success-color: #198754;
            --danger-color: #dc3545;
        }
        
        body {
            background: linear-gradient(135deg, #2E86AB 0%, #1B5E7A 100%);
            background-attachment: fixed;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                repeating-linear-gradient(0deg,
                    rgba(255, 255, 255, 0.05),
                    rgba(255, 255, 255, 0.05) 1px,
                    transparent 1px,
                    transparent 1.25rem),
                repeating-linear-gradient(90deg,
                    rgba(255, 255, 255, 0.05),
                    rgba(255, 255, 255, 0.05) 1px,
                    transparent 1px,
                    transparent 1.25rem);
            background-size: 1.25rem 1.25rem;
            z-index: -1;
        }
        
        .login-container {
            margin-top: 4rem;
            margin-bottom: 2rem;
        }
        
        .card {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.98);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
            border-bottom: none;
            padding: 2rem;
            text-align: center;
        }
        
        .card-header h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .card-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }
        
        .card-body {
            padding: 2.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }
        
        .form-label i {
            color: var(--primary-color);
            margin-left: 0.25rem;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 0.5rem;
            padding: 0.875rem 1rem;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(46, 134, 171, 0.25);
        }
        
        .btn-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            border: none;
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-custom:hover {
            background: linear-gradient(135deg, var(--primary-hover), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(46, 134, 171, 0.3);
        }
        
        .btn-custom:disabled {
            background: var(--secondary-color);
            transform: none;
            box-shadow: none;
        }
        
        .card-footer {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-top: 1px solid #e9ecef;
            padding: 1.5rem 2.5rem;
            text-align: center;
        }
        
        .highlight {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .highlight:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(46, 134, 171, 0.25);
        }
        
        .forgot-password {
            color: var(--accent-color);
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        
        .forgot-password:hover {
            color: #d67e01;
            text-decoration: underline;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .login-container {
                margin-top: 2rem;
                padding: 0 1rem;
            }
            
            .card-body {
                padding: 2rem;
            }
            
            .card-footer {
                padding: 1.5rem 2rem;
            }
        }
        
        /* Animation classes */
        .animate-pulse-custom {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
        
        /* Loading overlay */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }
        
        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <div class="container login-container">
        <div class="row justify-content-center animate__animated animate__fadeInDown">
            <div class="col-lg-5 col-md-7">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header">
                        <h4><i class="fas fa-sign-in-alt me-2"></i>Welcome Back</h4>
                        <p>Sign in to your account to continue shopping</p>
                    </div>
                    
                    <div class="card-body">
                        <form id="login-form" novalidate>
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    Email Address <i class="fas fa-envelope"></i>
                                </label>
                                <input type="email" 
                                       class="form-control animate__animated animate__fadeInUp" 
                                       id="email" 
                                       name="email" 
                                       placeholder="Enter your email address"
                                       maxlength="50"
                                       required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    Password <i class="fas fa-lock"></i>
                                </label>
                                <div class="position-relative">
                                    <input type="password" 
                                           class="form-control animate__animated animate__fadeInUp" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Enter your password"
                                           required>
                                    <button type="button" 
                                            class="btn btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2" 
                                            id="togglePassword"
                                            style="border: none; background: none; color: var(--secondary-color);">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rememberMe" name="remember_me">
                                        <label class="form-check-label" for="rememberMe">
                                            Remember me
                                        </label>
                                    </div>
                                    <a href="#" class="forgot-password">
                                        <i class="fas fa-key me-1"></i>Forgot Password?
                                    </a>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-custom w-100 animate-pulse-custom">
                                <i class="fas fa-sign-in-alt me-2"></i>Sign In
                            </button>
                        </form>
                    </div>
                    
                    <div class="card-footer">
                        <p class="mb-0">
                            Don't have an account? 
                            <a href="register.php" class="highlight">
                                <i class="fas fa-user-plus me-1"></i>Create Account
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/login.js"></script>
    
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                password.type = 'password';
                icon.className = 'fas fa-eye';
            }
        });
        
        // Show loading overlay on form submit
        document.getElementById('login-form').addEventListener('submit', function() {
            document.getElementById('loadingOverlay').style.display = 'block';
        });
    </script>
</body>
</html>

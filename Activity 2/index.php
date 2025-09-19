<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Commerce Platform - Home</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2E86AB;
            --primary-hover: #1B5E7A;
            --accent-color: #F18F01;
            --secondary-color: #6c757d;
            --light-blue: #E3F2FD;
        }
        
        body {
            background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 50%, #90CAF9 100%);
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
                    rgba(46, 134, 171, 0.1),
                    rgba(46, 134, 171, 0.1) 1px,
                    transparent 1px,
                    transparent 1.25rem),
                repeating-linear-gradient(90deg,
                    rgba(46, 134, 171, 0.1),
                    rgba(46, 134, 171, 0.1) 1px,
                    transparent 1px,
                    transparent 1.25rem);
            background-size: 1.25rem 1.25rem;
            z-index: -1;
        }
        
        .menu-tray {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid rgba(46, 134, 171, 0.2);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            box-shadow: 0 0.5rem 1rem rgba(46, 134, 171, 0.15);
            backdrop-filter: blur(10px);
            z-index: 1000;
        }
        
        .menu-tray .btn {
            margin-left: 0.5rem;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 0.25rem 0.5rem rgba(46, 134, 171, 0.3);
        }
        
        .btn-outline-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
        }
        
        .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
        }
        
        .btn-outline-danger:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 0.25rem 0.5rem rgba(220, 53, 69, 0.3);
        }
        
        .welcome-section {
            padding-top: 6rem;
            text-align: center;
        }
        
        .welcome-section h1 {
            color: var(--primary-color);
            font-weight: 800;
            margin-bottom: 1rem;
            font-size: 3rem;
        }
        
        .welcome-section .lead {
            color: var(--secondary-color);
            font-size: 1.2rem;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .user-info {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid var(--primary-color);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 0.5rem 1rem rgba(46, 134, 171, 0.1);
        }
        
        .features-section {
            margin-top: 4rem;
        }
        
        .feature-card {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(46, 134, 171, 0.2);
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            height: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 0.25rem 0.5rem rgba(46, 134, 171, 0.1);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 1rem 2rem rgba(46, 134, 171, 0.2);
            border-color: var(--primary-color);
        }
        
        .feature-card i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .feature-card h5 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .feature-card p {
            color: var(--secondary-color);
            font-size: 0.95rem;
        }
        
        .btn-ocean {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.875rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-ocean:hover {
            background: linear-gradient(135deg, var(--primary-hover), var(--primary-color));
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(46, 134, 171, 0.3);
        }
        
        .btn-outline-ocean {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
            border-radius: 0.5rem;
            padding: 0.875rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-ocean:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(46, 134, 171, 0.3);
        }
        
        .status-alert {
            background: linear-gradient(135deg, rgba(46, 134, 171, 0.1), rgba(27, 94, 122, 0.1));
            border: 2px solid var(--primary-color);
            border-radius: 1rem;
            padding: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Navigation Menu -->
    <div class="menu-tray">
        <span class="me-3 fw-semibold text-muted">
            <i class="fas fa-bars me-2"></i>Menu:
        </span>
        
        <?php if (isset($_SESSION['customer_id'])): ?>
            <!-- User is logged in -->
            <span class="me-2 text-primary fw-semibold">
                <i class="fas fa-user me-1"></i>
                Welcome, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>!
            </span>
            <a href="actions/logout_action.php" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-sign-out-alt me-1"></i>Logout
            </a>
        <?php else: ?>
            <!-- User is not logged in -->
            <a href="login/register.php" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-user-plus me-1"></i>Register
            </a>
            <a href="login/login.php" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-sign-in-alt me-1"></i>Login
            </a>
        <?php endif; ?>
    </div>

    <div class="container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <?php if (isset($_SESSION['customer_id'])): ?>
                        <!-- Logged in user welcome -->
                        <div class="user-info animate__animated animate__fadeInDown">
                            <h2><i class="fas fa-wave-square me-2 text-primary"></i>Welcome back, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>!</h2>
                            <p class="mb-0">
                                <i class="fas fa-envelope me-2 text-muted"></i>
                                <strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['customer_email']); ?>
                                <span class="mx-3">|</span>
                                <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                <strong>Location:</strong> <?php echo htmlspecialchars($_SESSION['customer_city'] . ', ' . $_SESSION['customer_country']); ?>
                                <span class="mx-3">|</span>
                                <i class="fas fa-user-tag me-2 text-muted"></i>
                                <strong>Role:</strong> <?php echo $_SESSION['user_role'] == 1 ? 'Administrator' : 'Customer'; ?>
                            </p>
                        </div>
                        
                        <h1><i class="fas fa-shopping-cart me-3"></i>Your Shopping Dashboard</h1>
                        <p class="lead">
                            Great to have you back! Explore our latest products and enjoy a seamless shopping experience tailored just for you.
                        </p>
                    <?php else: ?>
                        <!-- Guest user welcome -->
                        <h1><i class="fas fa-shopping-cart me-3"></i>Welcome to Our E-Commerce Platform</h1>
                        <p class="lead">
                            Your premier destination for online shopping. Create an account to explore our products and enjoy a seamless shopping experience.
                        </p>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <?php if (isset($_SESSION['customer_id'])): ?>
                            <!-- Logged in user actions -->
                            <a href="#" class="btn btn-lg btn-ocean">
                                <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                            </a>
                            <a href="#" class="btn btn-lg btn-outline-ocean">
                                <i class="fas fa-user-cog me-2"></i>My Account
                            </a>
                        <?php else: ?>
                            <!-- Guest user actions -->
                            <a href="login/register.php" class="btn btn-lg btn-ocean">
                                <i class="fas fa-user-plus me-2"></i>Get Started - Register Now
                            </a>
                            <a href="login/login.php" class="btn btn-lg btn-outline-ocean">
                                <i class="fas fa-sign-in-alt me-2"></i>Already a Member?
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Features Section -->
        <div class="features-section">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <i class="fas fa-shield-alt"></i>
                        <h5>Secure & Safe</h5>
                        <p>Your personal information and payments are protected with advanced encryption and security measures.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <i class="fas fa-shipping-fast"></i>
                        <h5>Fast Delivery</h5>
                        <p>Quick and reliable shipping options to get your orders delivered right to your doorstep in no time.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <i class="fas fa-headset"></i>
                        <h5>24/7 Support</h5>
                        <p>Our customer support team is available around the clock to assist you with any questions or concerns.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Status Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="status-alert text-center">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    <strong>Development Status:</strong> 
                    <?php if (isset($_SESSION['customer_id'])): ?>
                        Login system is fully functional! Product catalog and shopping cart features will be implemented in upcoming phases.
                    <?php else: ?>
                        Customer Registration and Login systems are now complete! Product management will be implemented in subsequent phases.
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Add some interactive elements
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.borderColor = '#2E86AB';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.borderColor = 'rgba(46, 134, 171, 0.2)';
            });
        });
        
        // Show welcome message for newly logged in users
        <?php if (isset($_SESSION['customer_id']) && isset($_GET['login_success'])): ?>
        // This would be set by the login action after redirect
        Swal.fire({
            icon: 'success',
            title: 'Welcome Back!',
            text: 'You have successfully logged in to your account.',
            confirmButtonColor: '#2E86AB',
            timer: 3000,
            timerProgressBar: true
        });
        <?php endif; ?>
    </script>
</body>
</html>

<?php
/**
 * Core settings and utility functions for the e-commerce platform
 * Handles session management, authentication, and common utilities
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable output buffering for header redirection
ob_start();

/**
 * Check if user is logged in
 * @return boolean True if logged in, false otherwise
 */
function is_logged_in()
{
    return isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']);
}

/**
 * Get current user ID
 * @return int|false User ID or false if not logged in
 */
function get_user_id()
{
    return is_logged_in() ? $_SESSION['customer_id'] : false;
}

/**
 * Get current user role
 * @return int|false User role or false if not logged in
 */
function get_user_role()
{
    return isset($_SESSION['user_role']) ? $_SESSION['user_role'] : false;
}

/**
 * Check if user has admin role
 * @return boolean True if admin, false otherwise
 */
function is_admin()
{
    return get_user_role() === 1;
}

/**
 * Check if user has customer role
 * @return boolean True if customer, false otherwise
 */
function is_customer()
{
    return get_user_role() === 2;
}

/**
 * Redirect user to login if not authenticated
 * @param string $redirect_url URL to redirect to after login
 * @return void
 */
function require_login($redirect_url = '../login/login.php')
{
    if (!is_logged_in()) {
        header("Location: " . $redirect_url);
        exit();
    }
}

/**
 * Redirect user to specific page based on role
 * @return void
 */
function redirect_by_role()
{
    if (is_admin()) {
        header("Location: ../admin/dashboard.php");
    } elseif (is_customer()) {
        header("Location: ../customer/dashboard.php");
    } else {
        header("Location: ../login/login.php");
    }
    exit();
}

/**
 * Logout user and destroy session
 * @param string $redirect_url URL to redirect after logout
 * @return void
 */
function logout_user($redirect_url = '../login/login.php')
{
    // Unset all session variables
    $_SESSION = array();

    // Delete session cookie if it exists
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }

    // Destroy the session
    session_destroy();

    // Redirect to login page
    header("Location: " . $redirect_url);
    exit();
}

/**
 * Set user session data after successful login
 * @param array $user_data User data from database
 * @return void
 */
function set_user_session($user_data)
{
    $_SESSION['customer_id'] = $user_data['customer_id'];
    $_SESSION['customer_name'] = $user_data['customer_name'];
    $_SESSION['customer_email'] = $user_data['customer_email'];
    $_SESSION['user_role'] = $user_data['user_role'];
    $_SESSION['login_time'] = time();
}

/**
 * Generate CSRF token for forms
 * @return string CSRF token
 */
function generate_csrf_token()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * @param string $token Token to verify
 * @return boolean True if valid, false otherwise
 */
function verify_csrf_token($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Sanitize input data
 * @param mixed $data Data to sanitize
 * @return mixed Sanitized data
 */
function sanitize_input($data)
{
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate random password
 * @param int $length Password length
 * @return string Random password
 */
function generate_random_password($length = 12)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $password = '';
    $charactersLength = strlen($characters);
    
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $password;
}

/**
 * Validate email format
 * @param string $email Email to validate
 * @return boolean True if valid, false otherwise
 */
function is_valid_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate password strength
 * @param string $password Password to validate
 * @return array Validation result with status and message
 */
function validate_password_strength($password)
{
    $result = ['valid' => false, 'message' => ''];
    
    if (strlen($password) < 8) {
        $result['message'] = 'Password must be at least 8 characters long';
        return $result;
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $result['message'] = 'Password must contain at least one lowercase letter';
        return $result;
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        $result['message'] = 'Password must contain at least one uppercase letter';
        return $result;
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $result['message'] = 'Password must contain at least one number';
        return $result;
    }
    
    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        $result['message'] = 'Password must contain at least one special character';
        return $result;
    }
    
    $result['valid'] = true;
    $result['message'] = 'Password is strong';
    return $result;
}

/**
 * Log activity for debugging and monitoring
 * @param string $message Log message
 * @param string $level Log level (info, warning, error)
 * @return void
 */
function log_activity($message, $level = 'info')
{
    $log_file = '../logs/activity.log';
    $timestamp = date('Y-m-d H:i:s');
    $user_id = get_user_id() ?: 'guest';
    $log_entry = "[{$timestamp}] [{$level}] [User: {$user_id}] {$message}" . PHP_EOL;
    
    // Create logs directory if it doesn't exist
    $log_dir = dirname($log_file);
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

/**
 * Format currency for display
 * @param float $amount Amount to format
 * @param string $currency Currency symbol
 * @return string Formatted currency string
 */
function format_currency($amount, $currency = 'GHS')
{
    return $currency . ' ' . number_format($amount, 2);
}

/**
 * Get time difference in human readable format
 * @param string $datetime Date time string
 * @return string Human readable time difference
 */
function time_ago($datetime)
{
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'just now';
    if ($time < 3600) return floor($time / 60) . ' minutes ago';
    if ($time < 86400) return floor($time / 3600) . ' hours ago';
    if ($time < 2592000) return floor($time / 86400) . ' days ago';
    if ($time < 31536000) return floor($time / 2592000) . ' months ago';
    
    return floor($time / 31536000) . ' years ago';
}
?>
<?php
header('Content-Type: application/json');
session_start();

$response = array();

// Check if user is already logged in
if (isset($_SESSION['customer_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'You are already logged in';
    echo json_encode($response);
    exit();
}

require_once '../controllers/customer_controller.php';

// Validate POST data exists
if (!isset($_POST['email']) || !isset($_POST['password'])) {
    $response['status'] = 'error';
    $response['message'] = 'Email and password are required';
    echo json_encode($response);
    exit();
}

// Sanitize input data
$email = trim($_POST['email']);
$password = $_POST['password'];

// Server-side validation
$errors = [];

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
}

// Validate password is not empty
if (empty($password)) {
    $errors[] = 'Password is required';
}

// Return validation errors if any
if (!empty($errors)) {
    $response['status'] = 'error';
    $response['message'] = implode('. ', $errors);
    echo json_encode($response);
    exit();
}

// Attempt to login customer
$login_result = login_customer_ctr($email, $password);

if ($login_result['success']) {
    // Set session variables as required
    $_SESSION['customer_id'] = $login_result['customer']['customer_id'];
    $_SESSION['customer_name'] = $login_result['customer']['customer_name'];
    $_SESSION['customer_email'] = $login_result['customer']['customer_email'];
    $_SESSION['user_role'] = $login_result['customer']['user_role'];
    $_SESSION['customer_country'] = $login_result['customer']['customer_country'];
    $_SESSION['customer_city'] = $login_result['customer']['customer_city'];
    $_SESSION['customer_contact'] = $login_result['customer']['customer_contact'];
    $_SESSION['login_time'] = time();
    
    // Log successful login
    error_log("Successful login for user: " . $email . " at " . date('Y-m-d H:i:s'));
    
    $response['status'] = 'success';
    $response['message'] = 'Login successful! Welcome back, ' . $login_result['customer']['customer_name'];
    $response['redirect'] = '../index.php';
} else {
    // Log failed login attempt
    error_log("Failed login attempt for email: " . $email . " at " . date('Y-m-d H:i:s'));
    
    $response['status'] = 'error';
    $response['message'] = $login_result['message'];
}

echo json_encode($response);
?>
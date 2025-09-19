<?php
require_once '../classes/customer_class.php';

/**
 * Customer controller functions that act as intermediary between views and models
 * Implements business logic and coordinates between different layers
 */

/**
 * Register a new customer
 * @param string $name Full name
 * @param string $email Email address
 * @param string $password Password
 * @param string $country Country
 * @param string $city City
 * @param string $contact Contact number
 * @param int $role User role (default: 2 for customer)
 * @return int|false Customer ID on success, false on failure
 */
function register_customer_ctr($name, $email, $password, $country, $city, $contact, $role = 2)
{
    $customer = new Customer();
    return $customer->addCustomer($name, $email, $password, $country, $city, $contact, $role);
}

/**
 * Login customer with email and password (Required for login functionality)
 * @param string $email Email address
 * @param string $password Password
 * @return array Login result with success status and customer data
 */
function login_customer_ctr($email, $password)
{
    $customer = new Customer();
    
    // Validate login credentials
    $login_result = $customer->validateLogin($email, $password);
    
    // If login successful, update last login time
    if ($login_result['success']) {
        $customer->updateLastLogin($login_result['customer']['customer_id']);
    }
    
    return $login_result;
}

/**
 * Get customer by email address
 * @param string $email Email address
 * @return array|false Customer data or false if not found
 */
function get_customer_by_email_ctr($email)
{
    $customer = new Customer();
    return $customer->getCustomerByEmail($email);
}

/**
 * Check if email already exists
 * @param string $email Email to check
 * @return bool True if exists, false otherwise
 */
function check_email_exists_ctr($email)
{
    $customer = new Customer();
    return $customer->emailExists($email);
}

/**
 * Edit customer information
 * @param int $customer_id Customer ID
 * @param string $name Full name
 * @param string $email Email
 * @param string $country Country
 * @param string $city City
 * @param string $contact Contact
 * @return bool Success status
 */
function edit_customer_ctr($customer_id, $name, $email, $country, $city, $contact)
{
    $customer = new Customer();
    return $customer->editCustomer($customer_id, $name, $email, $country, $city, $contact);
}

/**
 * Delete customer
 * @param int $customer_id Customer ID to delete
 * @return bool Success status
 */
function delete_customer_ctr($customer_id)
{
    $customer = new Customer();
    return $customer->deleteCustomer($customer_id);
}

/**
 * Get all customers
 * @return array|false Array of customers or false on failure
 */
function get_all_customers_ctr()
{
    $customer = new Customer();
    return $customer->getAllCustomers();
}

/**
 * Get customer by ID
 * @param int $customer_id Customer ID
 * @return Customer|false Customer object or false on failure
 */
function get_customer_by_id_ctr($customer_id)
{
    return new Customer($customer_id);
}
?>
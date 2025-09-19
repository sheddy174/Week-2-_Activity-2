<?php
require_once '../settings/db_class.php';

/**
 * Customer class that handles all customer-related database operations
 * Extends the database connection class for database operations
 */
class Customer extends db_connection
{
    private $customer_id;
    private $customer_name;
    private $customer_email;
    private $customer_pass;
    private $customer_country;
    private $customer_city;
    private $customer_contact;
    private $customer_image;
    private $user_role;

    /**
     * Constructor - Initialize database connection
     * @param int $customer_id Optional customer ID to load existing customer
     */
    public function __construct($customer_id = null)
    {
        parent::db_connect();
        if ($customer_id) {
            $this->customer_id = $customer_id;
            $this->loadCustomer();
        }
    }

    /**
     * Load customer data from database
     * @param int $customer_id Optional customer ID
     * @return bool Success status
     */
    private function loadCustomer($customer_id = null)
    {
        if ($customer_id) {
            $this->customer_id = $customer_id;
        }
        if (!$this->customer_id) {
            return false;
        }

        $stmt = $this->db->prepare("SELECT * FROM customer WHERE customer_id = ?");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $this->customer_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result) {
            $this->customer_name = $result['customer_name'];
            $this->customer_email = $result['customer_email'];
            $this->customer_country = $result['customer_country'];
            $this->customer_city = $result['customer_city'];
            $this->customer_contact = $result['customer_contact'];
            $this->customer_image = $result['customer_image'];
            $this->user_role = $result['user_role'];
            return true;
        }
        
        $stmt->close();
        return false;
    }

    /**
     * Add a new customer to the database
     * @param string $name Full name of customer
     * @param string $email Email address
     * @param string $password Plain text password (will be encrypted)
     * @param string $country Customer's country
     * @param string $city Customer's city
     * @param string $contact Contact number
     * @param int $role User role (1=admin, 2=customer)
     * @return int|false Customer ID on success, false on failure
     */
    public function addCustomer($name, $email, $password, $country, $city, $contact, $role = 2)
    {
        // Encrypt password using PHP's password_hash function
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("INSERT INTO customer (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact, user_role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ssssssi", $name, $email, $hashed_password, $country, $city, $contact, $role);
        
        if ($stmt->execute()) {
            $customer_id = $this->db->insert_id;
            $stmt->close();
            return $customer_id;
        }
        
        $stmt->close();
        return false;
    }

    /**
     * Get customer by email address (Required for login)
     * @param string $email Email address to search
     * @return array|false Customer data array or false if not found
     */
    public function getCustomerByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM customer WHERE customer_email = ?");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        return $result;
    }

    /**
     * Validate customer login credentials (Required for login)
     * @param string $email Email address
     * @param string $password Plain text password
     * @return array Login result with success status and customer data
     */
    public function validateLogin($email, $password)
    {
        // Get customer by email
        $customer = $this->getCustomerByEmail($email);
        
        if (!$customer) {
            return [
                'success' => false,
                'message' => 'Invalid email or password',
                'customer' => null
            ];
        }

        // Verify password against stored hash
        if (password_verify($password, $customer['customer_pass'])) {
            // Remove password from returned data for security
            unset($customer['customer_pass']);
            
            return [
                'success' => true,
                'message' => 'Login successful',
                'customer' => $customer
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Invalid email or password',
                'customer' => null
            ];
        }
    }

    /**
     * Check if email already exists in database
     * @param string $email Email to check
     * @return bool True if exists, false otherwise
     */
    public function emailExists($email)
    {
        $stmt = $this->db->prepare("SELECT customer_id FROM customer WHERE customer_email = ?");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();
        
        return $exists;
    }

    /**
     * Edit customer information
     * @param int $customer_id Customer ID to update
     * @param string $name New full name
     * @param string $email New email
     * @param string $country New country
     * @param string $city New city
     * @param string $contact New contact
     * @return bool Success status
     */
    public function editCustomer($customer_id, $name, $email, $country, $city, $contact)
    {
        $stmt = $this->db->prepare("UPDATE customer SET customer_name = ?, customer_email = ?, customer_country = ?, customer_city = ?, customer_contact = ? WHERE customer_id = ?");
        
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("sssssi", $name, $email, $country, $city, $contact, $customer_id);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }

    /**
     * Delete customer from database
     * @param int $customer_id Customer ID to delete
     * @return bool Success status
     */
    public function deleteCustomer($customer_id)
    {
        $stmt = $this->db->prepare("DELETE FROM customer WHERE customer_id = ?");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $customer_id);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }

    /**
     * Get all customers from database
     * @return array|false Array of customers or false on failure
     */
    public function getAllCustomers()
    {
        $sql = "SELECT customer_id, customer_name, customer_email, customer_country, customer_city, customer_contact, user_role FROM customer ORDER BY customer_name ASC";
        return $this->db_fetch_all($sql);
    }

    /**
     * Update last login time for customer
     * @param int $customer_id Customer ID
     * @return bool Success status
     */
    public function updateLastLogin($customer_id)
    {
        $stmt = $this->db->prepare("UPDATE customer SET last_login = NOW() WHERE customer_id = ?");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $customer_id);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }
}
?>
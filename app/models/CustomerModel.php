<?php
// app/models/CustomerModel.php
require_once __DIR__ . '/../../config/database.php';

class CustomerModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Lấy thông tin khách hàng theo ID
     */
    public function getCustomerById($customerId) {
        $customerId = mysqli_real_escape_string($this->db->link, $customerId);
        $sql = "SELECT * FROM tbl_customers WHERE customer_id = '$customerId' AND status = 'active'";
        $result = $this->db->select($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    /**
     * Lấy thông tin khách hàng theo email
     */
    public function getCustomerByEmail($email) {
        $email = mysqli_real_escape_string($this->db->link, $email);
        $sql = "SELECT * FROM tbl_customers WHERE email = '$email' AND status = 'active'";
        $result = $this->db->select($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    /**
     * Lấy thông tin khách hàng theo username
     */
    public function getCustomerByUsername($username) {
        $username = mysqli_real_escape_string($this->db->link, $username);
        $sql = "SELECT * FROM tbl_customers WHERE username = '$username' AND status = 'active'";
        $result = $this->db->select($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    /**
     * Cập nhật thông tin khách hàng
     */
    public function updateCustomer($customerId, $data) {
        $fields = [];
        
        foreach ($data as $field => $value) {
            if (in_array($field, ['full_name', 'phone', 'address', 'city', 'district', 'ward', 'birth_date', 'gender'])) {
                $escapedValue = $value === null ? 'NULL' : "'" . mysqli_real_escape_string($this->db->link, $value) . "'";
                $fields[] = "$field = $escapedValue";
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $customerId = mysqli_real_escape_string($this->db->link, $customerId);
        $sql = "UPDATE tbl_customers SET " . implode(', ', $fields) . " WHERE customer_id = '$customerId'";
        
        return $this->db->update($sql);
    }
    
    /**
     * Cập nhật mật khẩu
     */
    public function updatePassword($customerId, $hashedPassword) {
        $customerId = mysqli_real_escape_string($this->db->link, $customerId);
        $hashedPassword = mysqli_real_escape_string($this->db->link, $hashedPassword);
        
        $sql = "UPDATE tbl_customers SET password_hash = '$hashedPassword' WHERE customer_id = '$customerId'";
        return $this->db->update($sql);
    }
    
    /**
     * Tạo khách hàng mới
     */
    public function createCustomer($data) {
        $username = mysqli_real_escape_string($this->db->link, $data['username']);
        $email = mysqli_real_escape_string($this->db->link, $data['email']);
        $passwordHash = mysqli_real_escape_string($this->db->link, $data['password_hash']);
        $fullName = mysqli_real_escape_string($this->db->link, $data['full_name']);
        $phone = mysqli_real_escape_string($this->db->link, $data['phone']);
        
        $sql = "INSERT INTO tbl_customers (username, email, password_hash, full_name, phone, status) 
                VALUES ('$username', '$email', '$passwordHash', '$fullName', '$phone', 'active')";
        
        return $this->db->insert($sql);
    }
    
    /**
     * Kiểm tra email đã tồn tại chưa
     */
    public function emailExists($email, $excludeCustomerId = null) {
        $email = mysqli_real_escape_string($this->db->link, $email);
        $sql = "SELECT customer_id FROM tbl_customers WHERE email = '$email'";
        
        if ($excludeCustomerId) {
            $excludeCustomerId = mysqli_real_escape_string($this->db->link, $excludeCustomerId);
            $sql .= " AND customer_id != '$excludeCustomerId'";
        }
        
        $result = $this->db->select($sql);
        return $result && $result->num_rows > 0;
    }
    
    /**
     * Kiểm tra username đã tồn tại chưa
     */
    public function usernameExists($username, $excludeCustomerId = null) {
        $username = mysqli_real_escape_string($this->db->link, $username);
        $sql = "SELECT customer_id FROM tbl_customers WHERE username = '$username'";
        
        if ($excludeCustomerId) {
            $excludeCustomerId = mysqli_real_escape_string($this->db->link, $excludeCustomerId);
            $sql .= " AND customer_id != '$excludeCustomerId'";
        }
        
        $result = $this->db->select($sql);
        return $result && $result->num_rows > 0;
    }
    
    /**
     * Cập nhật lần đăng nhập cuối
     */
    public function updateLastLogin($customerId) {
        $customerId = mysqli_real_escape_string($this->db->link, $customerId);
        $sql = "UPDATE tbl_customers SET updated_at = CURRENT_TIMESTAMP WHERE customer_id = '$customerId'";
        return $this->db->update($sql);
    }
    
    /**
     * Lấy thống kê khách hàng
     */
    public function getCustomerStats($customerId) {
        $customerId = mysqli_real_escape_string($this->db->link, $customerId);
        $sql = "SELECT 
                    COUNT(DISTINCT o.order_id) as total_orders,
                    COALESCE(SUM(o.total_amount), 0) as total_spent,
                    COUNT(CASE WHEN o.order_status = 'delivered' THEN 1 END) as completed_orders,
                    COUNT(CASE WHEN o.order_status = 'cancelled' THEN 1 END) as cancelled_orders
                FROM tbl_order o 
                WHERE o.customer_id = '$customerId'";
        
        $result = $this->db->select($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return [
            'total_orders' => 0,
            'total_spent' => 0,
            'completed_orders' => 0,
            'cancelled_orders' => 0
        ];
    }
}
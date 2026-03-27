<?php
// app/models/AuthModel.php
require_once __DIR__ . '/../../config/database.php';

class AuthModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
        try {
        $this->db = new Database();
        // Test connection
        $testQuery = "SELECT 1";
        $testResult = $this->db->select($testQuery);
        error_log("Database connection test: " . ($testResult ? "SUCCESS" : "FAILED"));
        } catch (Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Đăng ký khách hàng mới
     */
    public function registerCustomer($username, $email, $password, $fullName, $phone = null) {
    error_log("AuthModel::registerCustomer called with email: $email, username: $username");
    
    try {
        // Kiểm tra email đã tồn tại chưa
        if ($this->emailExists($email)) {
            error_log("Email already exists: $email");
            return ['success' => false, 'message' => 'Email đã được sử dụng'];
        }
        
        // Kiểm tra username đã tồn tại chưa (nếu có)
        if ($username && $this->usernameExists($username)) {
            error_log("Username already exists: $username");
            return ['success' => false, 'message' => 'Tên đăng nhập đã được sử dụng'];
        }
        
        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        error_log("Password hashed successfully");
        
        // Escape strings to prevent SQL injection (tạm thời)
        $username = mysqli_real_escape_string($this->db->link, $username);
        $email = mysqli_real_escape_string($this->db->link, $email);
        $fullName = mysqli_real_escape_string($this->db->link, $fullName);
        $phone = $phone ? mysqli_real_escape_string($this->db->link, $phone) : null;
        
        // Insert customer mới
        $query = "INSERT INTO tbl_customers (username, email, password_hash, full_name, phone, status, created_at) 
                  VALUES ('$username', '$email', '$passwordHash', '$fullName', " . ($phone ? "'$phone'" : "NULL") . ", 'active', NOW())";
        
        error_log("Executing query: $query");
        
        $result = $this->db->insert($query);
        
        error_log("Database insert result: " . ($result ? 'SUCCESS' : 'FAILED'));
        
        if ($result) {
            return ['success' => true, 'message' => 'Đăng ký thành công'];
        } else {
            return ['success' => false, 'message' => 'Có lỗi xảy ra khi đăng ký - Database insert failed'];
        }
        
    } catch (Exception $e) {
        error_log("Exception in registerCustomer: " . $e->getMessage());
        return ['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()];
    }
    }
    
    /**
     * Đăng nhập khách hàng
     */
    public function loginCustomer($emailOrUsername, $password) {
    error_log("AuthModel::loginCustomer called with: '$emailOrUsername'");
    
    try {
        // Escape để tránh SQL injection
        $emailOrUsername = mysqli_real_escape_string($this->db->link, $emailOrUsername);
        
        // Tìm customer theo email hoặc username
        $query = "SELECT customer_id, username, email, password_hash, full_name, phone, status 
                  FROM tbl_customers 
                  WHERE (email = '$emailOrUsername' OR username = '$emailOrUsername') 
                  AND status = 'active'";
        
        error_log("Executing query: $query");
        
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            $customer = $result->fetch_assoc();
            error_log("Found customer: " . print_r($customer, true));
            
            // Verify password
            if (password_verify($password, $customer['password_hash'])) {
                error_log("Password verification successful");
                
                // Update last login
                $updateQuery = "UPDATE tbl_customers SET updated_at = NOW() WHERE customer_id = " . $customer['customer_id'];
                $this->db->update($updateQuery);
                
                return [
                    'success' => true,
                    'customer' => $customer,
                    'message' => 'Đăng nhập thành công'
                ];
            } else {
                error_log("Password verification failed");
                return ['success' => false, 'message' => 'Mật khẩu không chính xác'];
            }
        } else {
            error_log("No customer found with credentials: '$emailOrUsername'");
            
            // Debug: Check if any customers exist
            $debugQuery = "SELECT email, username FROM tbl_customers LIMIT 5";
            $debugResult = $this->db->select($debugQuery);
            if ($debugResult) {
                error_log("Sample customers in database:");
                while($row = $debugResult->fetch_assoc()) {
                    error_log("- Email: " . $row['email'] . ", Username: " . $row['username']);
                }
            }
            
            return ['success' => false, 'message' => 'Tài khoản không tồn tại hoặc đã bị khóa'];
        }
        
    } catch (Exception $e) {
        error_log("Exception in loginCustomer: " . $e->getMessage());
        return ['success' => false, 'message' => 'Có lỗi database: ' . $e->getMessage()];
    }
    }
    
    /**
 * Đăng nhập admin - CẬP NHẬT
 */
    public function loginAdmin($username, $password) {
        $username = mysqli_real_escape_string($this->db->link, $username);
        
        $query = "SELECT admin_id, admin_name, admin_password, idgroup, email, full_name 
                FROM tbl_admin 
                WHERE admin_name = '$username' AND status = 'active'";
        
        error_log("Admin login query: " . $query);
        
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            
            error_log("Found admin: " . print_r($admin, true));
            
            // So sánh password (chưa hash trong bảng hiện tại)
            if ($admin['admin_password'] === $password) {
                // Update last login
                $updateQuery = "UPDATE tbl_admin SET last_login = NOW() WHERE admin_id = " . $admin['admin_id'];
                $this->db->update($updateQuery);
                
                return [
                    'success' => true,
                    'admin' => $admin,
                    'message' => 'Đăng nhập admin thành công'
                ];
            } else {
                return ['success' => false, 'message' => 'Mật khẩu admin không chính xác'];
            }
        } else {
            return ['success' => false, 'message' => 'Tài khoản admin không tồn tại'];
        }
    }
    
    /**
     * Kiểm tra email đã tồn tại
     */
    private function emailExists($email) {
        $query = "SELECT customer_id FROM tbl_customers WHERE email = '$email'";
        $result = $this->db->select($query);
        return ($result && $result->num_rows > 0);
    }
    
    /**
     * Kiểm tra username đã tồn tại
     */
    private function usernameExists($username) {
        if (empty($username)) return false;
        
        $query = "SELECT customer_id FROM tbl_customers WHERE username = '$username'";
        $result = $this->db->select($query);
        return ($result && $result->num_rows > 0);
    }
    
    /**
     * Lấy thông tin customer theo ID
     */
    public function getCustomerById($customerId) {
        $query = "SELECT customer_id, username, email, full_name, phone, address, city, district, ward, birth_date, gender, status, created_at 
                  FROM tbl_customers 
                  WHERE customer_id = '$customerId' AND status = 'active'";
        
        $result = $this->db->select($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Cập nhật thông tin customer
     */
    public function updateCustomer($customerId, $data) {
        $setParts = [];
        
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $setParts[] = "$key = '$value'";
            }
        }
        
        if (empty($setParts)) {
            return false;
        }
        
        $query = "UPDATE tbl_customers SET " . implode(', ', $setParts) . ", updated_at = NOW() WHERE customer_id = '$customerId'";
        
        return $this->db->update($query);
    }
}
?>
<?php
// app/controllers/frontend/AuthController.php
require_once __DIR__ . '/../../models/AuthModel.php';

class AuthController {
    private $authModel;
    
    public function __construct() {
        $this->authModel = new AuthModel();
        
    }
    
    /**
     * Hiển thị trang đăng nhập
     */
    public function showLogin() {
        // Nếu đã đăng nhập thì redirect
        if ($this->isLoggedIn()) {
            header('Location: index.php');
            exit;
        }
        
        $data = [
            'pageTitle' => 'Đăng nhập - VoxFootball',
            'pageClass' => 'login-page'
        ];
        
        $this->loadView('login', $data);
    }
    
    /**
     * Xử lý đăng nhập khách hàng
     */
    public function login() {
        // Clear any previous output
        ob_clean();
        
        header('Content-Type: application/json');
        error_log("=== CUSTOMER LOGIN ATTEMPT START ===");
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        $emailOrUsername = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        error_log("Customer login data - Username: '$emailOrUsername'");
        
        // Validate input
        if (empty($emailOrUsername) || empty($password)) {
            error_log("Empty credentials");
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin']);
            exit;
        }
        
        try {
            error_log("Attempting customer login");
            $result = $this->authModel->loginCustomer($emailOrUsername, $password);
            
            error_log("Customer login result: " . print_r($result, true));
            
            if ($result['success']) {
                // Set customer session
                Session::set('customer_id', $result['customer']['customer_id']);
                Session::set('customer_name', $result['customer']['full_name']);
                Session::set('customer_email', $result['customer']['email']);
                Session::set('user_role', 'customer');
                
                error_log("Customer session set successfully");
                
                echo json_encode([
                    'success' => true, 
                    'message' => $result['message'],
                    'redirect' => 'index.php'
                ]);
            } else {
                error_log("Customer login failed: " . $result['message']);
                echo json_encode(['success' => false, 'message' => $result['message']]);
            }
        } catch (Exception $e) {
            error_log('Customer login exception: ' . $e->getMessage() . " - " . $e->getTraceAsString());
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
        
        exit;
    }
    
    /**
     * Hiển thị trang đăng ký
     */
    public function showRegister() {
        // Nếu đã đăng nhập thì redirect
        if ($this->isLoggedIn()) {
            header('Location: index.php');
            exit;
        }
        
        $data = [
            'pageTitle' => 'Đăng ký - VoxFootball',
            'pageClass' => 'register-page'
        ];
        
        $this->loadView('signup', $data);
    }
    
    /**
     * Xử lý đăng ký
     */
    public function register() {
    // Clear any previous output
    ob_clean();
    
    header('Content-Type: application/json');
    
    // Debug POST data
    error_log("Register attempt - POST data: " . print_r($_POST, true));
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }
    
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    error_log("Username: $username, Email: $email");
    
    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        error_log("Missing required fields");
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin']);
        exit;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid email format: $email");
        echo json_encode(['success' => false, 'message' => 'Email không hợp lệ']);
        exit;
    }
    
    // Check password match
    if ($password !== $confirmPassword) {
        error_log("Password mismatch");
        echo json_encode(['success' => false, 'message' => 'Mật khẩu xác nhận không khớp']);
        exit;
    }
    
    // Check password strength
    if (strlen($password) < 6) {
        error_log("Password too short");
        echo json_encode(['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự']);
        exit;
    }
    
    try {
        error_log("Calling registerCustomer...");
        $result = $this->authModel->registerCustomer($username, $email, $password, $username);
        
        error_log("Register result: " . print_r($result, true));
        
        if ($result['success']) {
            echo json_encode([
                'success' => true, 
                'message' => $result['message'],
                'redirect' => 'index.php?page=login'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => $result['message']]);
        }
    } catch (Exception $e) {
        error_log('Register exception: ' . $e->getMessage() . " - " . $e->getTraceAsString());
        echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
    }
    
    exit;
    }
    
    /**
     * Đăng xuất khách hàng
     */
    public function logout() {
        // Chỉ xóa session khách hàng, không ảnh hưởng admin session
        unset($_SESSION['customer_id']);
        unset($_SESSION['customer_name']);
        unset($_SESSION['customer_email']);
        
        $_SESSION['success'] = 'Đã đăng xuất thành công';
        
        header('Location: index.php');
        exit;
    }
    
    /**
     * Kiểm tra khách hàng đã đăng nhập chưa (chỉ frontend)
     */
    private function isLoggedIn() {
        return Session::get('customer_id') ? true : false;
    }
    
    /**
     * Load view với layout
     */
    private function loadView($view, $data = []) {
        extract($data);
        // include APP_PATH . '/views/layouts/header.php';
        include APP_PATH . '/views/frontend_views/' . $view . '.php';
        // include APP_PATH . '/views/layouts/footer.php';
    }
}
?>
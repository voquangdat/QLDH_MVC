<?php
// app/controllers/admin/AdminAuthController.php
require_once __DIR__ . '/../../models/AuthModel.php';
// require_once __DIR__ . '/../../config/session.php';

class AdminAuthController {
    private $authModel;
    
    public function __construct() {
        $this->authModel = new AuthModel();
        
        // Đảm bảo session được khởi tạo
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Hiển thị trang đăng nhập admin
     */
    public function showLogin() {
        // Nếu đã đăng nhập, redirect đến dashboard
        if ($this->isAdminLoggedIn()) {
            header('Location: index.php?page=admin&action=dashboard');
            exit;
        }
        
                $this->loadView('admin/login');
    }
    
    /**
     * Xử lý đăng nhập admin
     */
    public function login() {
        // Chỉ xử lý POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showLogin();
            return;
        }
        
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $idgroup = 1; // Giả sử nhóm admin có idgroup = 1
        
        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin';
            $this->showLogin();
            return;
        }
        
        // Kiểm tra thông tin đăng nhập admin
        $result = $this->authModel->loginAdmin($username, $password);
        
        if ($result['success']) {
            $admin = $result['admin'];
            
            // Lưu thông tin admin vào session
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['admin_name']; // Sửa thành admin_name
            $_SESSION['user_role'] = 'admin';
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['idgroup'] = $admin['idgroup'];
            
            // Trả về JSON response cho AJAX
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Đăng nhập thành công',
                    'redirect' => 'index.php?page=admin&action=dashboard'
                ]);
                exit;
            }
            
            // Redirect đến dashboard (cho non-AJAX request)
            header('Location: index.php?page=admin&action=dashboard');
            exit;
        } else {
            // Trả về JSON error cho AJAX
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $result['message']
                ]);
                exit;
            }
            
            $_SESSION['error'] = $result['message'];
            $this->showLogin();
        }
    }
    
    /**
     * Hiển thị dashboard admin
     */
    public function dashboard() {
        // Kiểm tra đăng nhập
        if (!$this->isAdminLoggedIn()) {
            header('Location: index.php?page=admin');
            exit;
        }
        
        $data = [
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin'
        ];
        
        $this->loadView('admin/dashboard', $data);
    }
    
    /**
     * Đăng xuất admin
     */
    public function logout() {
        // Xóa session admin
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        unset($_SESSION['user_role']);
        unset($_SESSION['admin_logged_in']);
        unset($_SESSION['idgroup']);
        
        $_SESSION['success'] = 'Đã đăng xuất thành công';
        
        // Redirect về trang đăng nhập
        header('Location: index.php?page=admin');
        exit;
    }
    
    /**
     * Kiểm tra admin đã đăng nhập chưa
     */
    private function isAdminLoggedIn() {
        return isset($_SESSION['admin_logged_in']) && 
               $_SESSION['admin_logged_in'] === true && 
               isset($_SESSION['admin_id']) &&
               $_SESSION['user_role'] === 'admin';
    }
    
    /**
     * Load view với layout
     */
    private function loadView($view, $data = []) {
        extract($data);
        
        if (strpos($view, 'admin/') === 0) {
            // Admin views
            include APP_PATH . '/views/' . $view . '.php';
        } else {
            // Frontend views với layout
            include APP_PATH . '/views/layouts/header.php';
            include APP_PATH . '/views/frontend_views/' . $view . '.php';
            include APP_PATH . '/views/layouts/footer.php';
        }
    }
}
?>